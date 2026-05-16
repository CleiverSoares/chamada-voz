<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PersonaController extends Controller
{
    public function create()
    {
        return view('estudio');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'dificuldade' => 'required|string',
            'emoji' => 'required|string',
            'primeira_mensagem' => 'required|string',
            'prompt' => 'required|string',
            'voce_provider' => 'required|string',
            'voce_id' => 'required|string',
        ]);

        $vapiKey = env('VAPI_PRIVATE_KEY');
        if (!$vapiKey) {
            return back()->withError('VAPI_PRIVATE_KEY não configurada no .env');
        }

        // 1. Enviar para a Vapi
        $payload = [
            'name' => $request->nome,
            'firstMessage' => $request->primeira_mensagem,
            'model' => [
                'provider' => 'openai',
                'model' => 'gpt-4o', // ou gpt-3.5-turbo
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $request->prompt
                    ]
                ]
            ],
            'voice' => [
                'provider' => $request->voce_provider,
                'voiceId' => $request->voce_id
            ],
            'analysisPlan' => [
                'structuredDataPlan' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'score_empatia' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a empatia e cordialidade do vendedor.'],
                            'score_conhecimento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando o conhecimento demonstrado sobre o produto.'],
                            'score_objecao' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a capacidade de contornar objeções do cliente.'],
                            'score_fechamento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a tentativa e qualidade do fechamento da venda.'],
                        ]
                    ]
                ]
            ]
        ];

        $response = Http::withToken($vapiKey)->post('https://api.vapi.ai/assistant', $payload);

        if (!$response->successful()) {
            return back()->withError('Erro na API da Vapi: ' . $response->body())->withInput();
        }

        $assistant = $response->json();
        $assistantId = $assistant['id'];

        // 2. Salvar no Banco
        $chave = Str::slug($request->nome, '_') . '_' . rand(100, 999);

        Persona::create([
            'chave' => $chave,
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'dificuldade' => $request->dificuldade,
            'emoji' => $request->emoji,
            'assistant_id' => $assistantId,
            'prompt' => $request->prompt,
            'primeira_mensagem' => $request->primeira_mensagem,
            'voce_provider' => $request->voce_provider,
            'voce_id' => $request->voce_id,
        ]);

        return redirect()->route('selecionar')->with('success', 'Persona gerada e ativada com sucesso!');
    }

    public function sync()
    {
        $vapiKey = env('VAPI_PRIVATE_KEY');
        if (!$vapiKey) {
            return back()->withError('VAPI_PRIVATE_KEY não configurada no .env');
        }

        $response = Http::withToken($vapiKey)->get('https://api.vapi.ai/assistant');

        if (!$response->successful()) {
            return back()->withError('Erro ao buscar assistentes na Vapi: ' . $response->body());
        }

        $assistants = $response->json();
        $novos = 0;
        $atualizados = 0;
        $vapiIds = [];

        foreach ($assistants as $assistant) {
            // Ignora se não tem nome
            if (empty($assistant['name'])) continue;
            
            $vapiIds[] = $assistant['id'];

            $nome = $assistant['name'] ?? 'Sem Nome';
            $primeiraMensagem = $assistant['firstMessage'] ?? null;
            
            // Extrair prompt
            $prompt = null;
            if (!empty($assistant['model']['messages']) && is_array($assistant['model']['messages'])) {
                foreach ($assistant['model']['messages'] as $msg) {
                    if (($msg['role'] ?? '') === 'system') {
                        $prompt = $msg['content'] ?? null;
                        break;
                    }
                }
            }

            $voce_provider = $assistant['voice']['provider'] ?? 'vapi';
            $voce_id = $assistant['voice']['voiceId'] ?? null;

            $persona = Persona::where('assistant_id', $assistant['id'])->first();
            
            if ($persona) {
                // Se já existe, atualiza
                $persona->update([
                    'prompt' => $prompt ?: $persona->prompt,
                    'primeira_mensagem' => $primeiraMensagem ?: $persona->primeira_mensagem,
                    'voce_provider' => $voce_provider,
                    'voce_id' => $voce_id,
                ]);
                $atualizados++;
            } else {
                // Persona nova
                Persona::create([
                    'chave' => Str::slug($nome, '_') . '_' . rand(100, 999),
                    'nome' => $nome,
                    'descricao' => 'Importado da Vapi. (Edite para adicionar detalhes)',
                    'dificuldade' => 'Médio', // Padrão
                    'emoji' => '🤖',
                    'assistant_id' => $assistant['id'],
                    'prompt' => $prompt,
                    'primeira_mensagem' => $primeiraMensagem,
                    'voce_provider' => $voce_provider,
                    'voce_id' => $voce_id,
                ]);
                $novos++;
            }
        }

        // Remover localmente as personas que não existem mais na Vapi
        $removidos = 0;
        if (count($vapiIds) > 0) {
            $removidos = Persona::whereNotIn('assistant_id', $vapiIds)->count();
            Persona::whereNotIn('assistant_id', $vapiIds)->delete();
        }

        return redirect()->route('selecionar')->with('success', "Sync concluído! $novos adicionadas, $atualizados atualizadas e $removidos removidas.");
    }

    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        return view('estudio', compact('persona'));
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'dificuldade' => 'required|string',
            'emoji' => 'required|string',
            'primeira_mensagem' => 'required|string',
            'prompt' => 'required|string',
            'voce_provider' => 'required|string',
            'voce_id' => 'required|string',
        ]);

        $vapiKey = env('VAPI_PRIVATE_KEY');
        if (!$vapiKey) {
            return back()->withError('VAPI_PRIVATE_KEY não configurada no .env');
        }

        // Atualizar na Vapi
        $payload = [
            'name' => $request->nome,
            'firstMessage' => $request->primeira_mensagem,
            'model' => [
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $request->prompt
                    ]
                ]
            ],
            'voice' => [
                'provider' => $request->voce_provider,
                'voiceId' => $request->voce_id
            ],
            'analysisPlan' => [
                'structuredDataPlan' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'score_empatia' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a empatia e cordialidade do vendedor.'],
                            'score_conhecimento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando o conhecimento demonstrado sobre o produto.'],
                            'score_objecao' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a capacidade de contornar objeções do cliente.'],
                            'score_fechamento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a tentativa e qualidade do fechamento da venda.'],
                        ]
                    ]
                ]
            ]
        ];

        // PATCH na Vapi
        $response = Http::withToken($vapiKey)->patch('https://api.vapi.ai/assistant/' . $persona->assistant_id, $payload);

        if (!$response->successful()) {
            return back()->withError('Erro ao atualizar na Vapi: ' . $response->body())->withInput();
        }

        // Atualizar no Banco local
        $persona->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'dificuldade' => $request->dificuldade,
            'emoji' => $request->emoji,
            'prompt' => $request->prompt,
            'primeira_mensagem' => $request->primeira_mensagem,
            'voce_provider' => $request->voce_provider,
            'voce_id' => $request->voce_id,
        ]);

        return redirect()->route('selecionar')->with('success', 'Persona atualizada com sucesso na Vapi!');
    }
}
