<?php

namespace App\Http\Controllers;

use App\Models\Simulacao;
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{
    public static function nomePersona(string $key): string
    {
        return match($key) {
            'seu_mario'              => 'Antônio',
            'dona_sonia'             => 'Sônia',
            'flavio_academia_vendas' => 'Flávio — Mentor Elite',
            default                  => ucwords(str_replace('_', ' ', $key)),
        };
    }

    public static function emojiPersona(string $key): string
    {
        return match($key) {
            'seu_mario'              => '🏪',
            'dona_sonia'             => '👩‍💼',
            'flavio_academia_vendas' => '🏆',
            default                  => '🎯',
        };
    }

    public function index()
    {
        return view('index');
    }

    public function selecionar()
    {
        $personas = [
            'seu_mario' => [
                'nome' => 'Antônio',
                'descricao' => 'Dono de supermercado de bairro. Apressado, desconfiado e focado em custos.',
                'dificuldade' => 'Difícil',
                'assistant_id' => env('VAPI_ASSISTANT_SEU_ANTONIO'),
            ],
            'dona_sonia' => [
                'nome' => 'Sônia',
                'descricao' => 'Contadora de 58 anos. Metódica, educada, mas resistente a mudanças. Usa vários sistemas separados.',
                'dificuldade' => 'Muito Difícil',
                'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
            ],
            'flavio_academia_vendas' => [
                'nome' => 'Flávio — Mentor Elite',
                'descricao' => 'Mentor da Academia de Vendas Alterdata. Treina pitch, simula clientes sob demanda e dá feedback cirúrgico em tempo real.',
                'dificuldade' => 'Treinamento',
                'assistant_id' => env('VAPI_ASSISTANT_FLAVIO_ACAD_VENDAS'),
            ],
        ];

        return view('selecionar', compact('personas'));
    }

    public function iniciar(Request $request)
    {
        $request->validate([
            'persona' => 'required|string',
            'vendedor_nome' => 'required|string|max:255',
            'duracao' => 'required|integer|min:0|max:600',
        ]);

        $simulacao = Simulacao::create([
            'persona' => $request->persona,
            'vendedor_nome' => $request->vendedor_nome,
            'status' => 'em_andamento',
            'duracao_segundos' => $request->duracao,
        ]);

        return redirect()->route('combate', $simulacao->id);
    }

    public function combate(Simulacao $simulacao)
    {
        if ($simulacao->status !== 'em_andamento') {
            return redirect()->route('resultado', $simulacao->id);
        }

        $personas = [
            'seu_mario' => [
                'nome' => 'Antônio',
                'assistant_id' => env('VAPI_ASSISTANT_SEU_ANTONIO'),
            ],
            'dona_sonia' => [
                'nome' => 'Sônia',
                'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
            ],
            'flavio_academia_vendas' => [
                'nome' => 'Flávio Academia de Vendas',
                'assistant_id' => env('VAPI_ASSISTANT_FLAVIO_ACAD_VENDAS'),
            ],
        ];

        $personaData = $personas[$simulacao->persona] ?? [
            'nome' => ucfirst(str_replace('_', ' ', $simulacao->persona)),
            'assistant_id' => null,
        ];

        return view('combate', [
            'simulacao' => $simulacao,
            'persona' => $personaData,
            'vapiPublicKey' => env('VAPI_PUBLIC_KEY'),
            'duracaoMaxima' => $simulacao->duracao_segundos ?? 120,
        ]);
    }

    public function status(Simulacao $simulacao)
    {
        return response()->json([
            'status' => $simulacao->status,
            'score' => $simulacao->score,
        ]);
    }

    public function resultado(Simulacao $simulacao)
    {
        if ($simulacao->status !== 'concluido') {
            return redirect()->route('combate', $simulacao->id);
        }

        return view('resultado', compact('simulacao'));
    }

    public function historico()
    {
        $simulacoes = Simulacao::where('status', 'concluido')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $estatisticas = [
            'total' => Simulacao::where('status', 'concluido')->count(),
            'media_score' => round(Simulacao::where('status', 'concluido')->avg('score'), 1),
            'melhor_score' => Simulacao::where('status', 'concluido')->max('score'),
            'total_tempo' => Simulacao::where('status', 'concluido')->sum('duracao_segundos'),
        ];
        
        return view('historico', compact('simulacoes', 'estatisticas'));
    }
}
