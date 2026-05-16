<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Simulacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VapiWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $type = $request->input('message.type');

        // Evento de fim de chamada
        if ($type === 'end-of-call-report') {
            $callId = $request->input('message.call.id');
            $transcript = $request->input('message.transcript', '');
            $recordingUrl = $request->input('message.recordingUrl');
            $startedAt = $request->input('message.startedAt');
            $endedAt = $request->input('message.endedAt');
            
            // VARREDURA TOTAL: Busca nos 3 caminhos possíveis da Vapi
            $resultado = data_get($payload, 'message.analysis.structuredData.result') 
                ?? data_get($payload, 'message.artifact.structuredData') 
                ?? null;

            // Se não achou, tenta no formato de array (nova interface)
            if (!$resultado) {
                $outputs = data_get($payload, 'message.artifact.structuredOutputs', []);
                foreach ($outputs as $output) {
                    if (isset($output['result']['score']) || isset($output['result']['score_empatia'])) {
                        $resultado = $output['result'];
                        break;
                    }
                }
            }
            
            Log::info('Webhook recebido', [
                'call_id' => $callId, 
                'type' => $type,
                'tem_analise' => !empty($resultado) && (isset($resultado['score']) || isset($resultado['score_empatia']))
            ]);
            
            // Calcular duração
            $duration = 0;
            if ($startedAt && $endedAt) {
                $duration = strtotime($endedAt) - strtotime($startedAt);
            }

            // Buscar simulação
            $simulacao = Simulacao::where('call_id', $callId)
                ->orWhere('status', 'em_andamento')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($simulacao) {
                // PEGAR OS DADOS DA ANÁLISE DA VAPI (se veio)
                if ($resultado && (isset($resultado['score']) || isset($resultado['score_empatia']))) {
                    // Se a Vapi mandou as 4 dimensões (score_empatia, etc), calculamos a média para o score principal
                    if (isset($resultado['score_empatia'])) {
                        $e = $resultado['score_empatia'] ?? 0;
                        $c = $resultado['score_conhecimento'] ?? 0;
                        $o = $resultado['score_objecao'] ?? 0;
                        $f = $resultado['score_fechamento'] ?? 0;
                        $score = round(($e + $c + $o + $f) / 4);
                    } else {
                        $score = $resultado['score'] ?? 50;
                    }
                    
                    $pontosPositivos = $resultado['pontos_positivos'] ?? 'Boa tentativa de interação.';
                    $pontosMelhoria = $resultado['pontos_melhoria'] ?? 'Análise detalhada não fornecida pela inteligência.';
                    $fonteAnalise = 'Vapi IA';
                } else {
                    // Fallback automático
                    $messages = $request->input('message.artifact.messages', []);
                    $score = $this->calcularScore($messages, $duration);
                    $feedback = $this->gerarFeedback($messages, $duration);
                    $pontosPositivos = $feedback['positivos'];
                    $pontosMelhoria = $feedback['melhoria'];
                    $fonteAnalise = 'Fallback';
                    
                    Log::warning('⚠️ Análise não veio da Vapi, usando fallback');
                }

                $simulacao->update([
                    'status' => 'concluido',
                    'call_id' => $callId,
                    'transcricao' => $transcript ?: 'Transcrição não disponível',
                    'transcricao_json' => json_encode($request->input('message.artifact.messages', [])),
                    'score' => $score,
                    'score_empatia' => $resultado['score_empatia'] ?? null,
                    'score_conhecimento' => $resultado['score_conhecimento'] ?? null,
                    'score_objecao' => $resultado['score_objecao'] ?? null,
                    'score_fechamento' => $resultado['score_fechamento'] ?? null,
                    'pontos_positivos' => $pontosPositivos,
                    'pontos_melhoria' => $pontosMelhoria,
                    'recording_url' => $recordingUrl,
                    'duracao_segundos' => $duration,
                ]);

                Log::info('✅ Simulação concluída', [
                    'id' => $simulacao->id,
                    'vendedor' => $simulacao->vendedor_nome,
                    'score' => $score,
                    'duracao' => gmdate('i:s', $duration),
                    'fonte_analise' => $fonteAnalise
                ]);
            } else {
                Log::warning('❌ Simulação não encontrada', ['call_id' => $callId]);
            }
        }

        return response()->json(['success' => true]);
    }

    private function calcularScore($messages, $duration)
    {
        $score = 50; // Base
        
        // Pontos por duração (máximo 20 pontos)
        if ($duration > 60) $score += 20;
        elseif ($duration > 30) $score += 15;
        elseif ($duration > 15) $score += 10;
        
        // Pontos por número de mensagens do usuário (máximo 30 pontos)
        $userMessages = collect($messages)->where('role', 'user')->count();
        if ($userMessages >= 5) $score += 30;
        elseif ($userMessages >= 3) $score += 20;
        elseif ($userMessages >= 2) $score += 10;
        
        return min(100, $score);
    }

    private function gerarFeedback($messages, $duration)
    {
        $userMessages = collect($messages)->where('role', 'user')->count();
        
        $positivos = [];
        $melhoria = [];
        
        // Análise de duração
        if ($duration > 60) {
            $positivos[] = "Conseguiu manter uma conversa prolongada (" . gmdate('i:s', $duration) . ")";
        } else {
            $melhoria[] = "Tente estender a conversa para entender melhor as necessidades do cliente";
        }
        
        // Análise de interação
        if ($userMessages >= 4) {
            $positivos[] = "Boa interação com múltiplas respostas e argumentos";
        } elseif ($userMessages <= 2) {
            $melhoria[] = "Faça mais perguntas e apresente mais argumentos";
        }
        
        // Feedback padrão
        $positivos[] = "Apresentação inicial clara";
        $positivos[] = "Manteve profissionalismo durante a chamada";
        
        $melhoria[] = "Trabalhe mais a quebra de objeções";
        $melhoria[] = "Seja mais assertivo no fechamento";
        
        return [
            'positivos' => implode("\n• ", $positivos),
            'melhoria' => implode("\n• ", $melhoria)
        ];
    }
}
