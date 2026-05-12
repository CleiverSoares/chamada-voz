<?php

namespace App\Http\Controllers;

use App\Models\Simulacao;
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function selecionar()
    {
        $personas = [
            'seu_mario' => [
                'nome' => 'Seu Mário',
                'descricao' => 'Dono de supermercado de bairro. Apressado, desconfiado e focado em custos.',
                'dificuldade' => 'Difícil',
                'assistant_id' => env('VAPI_ASSISTANT_SEU_MARIO'),
            ],
            'dona_sonia' => [
                'nome' => 'Dona Sônia',
                'descricao' => 'Contadora de 58 anos. Metódica, educada, mas resistente a mudanças. Usa vários sistemas separados.',
                'dificuldade' => 'Muito Difícil',
                'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
            ],
        ];

        return view('selecionar', compact('personas'));
    }

    public function iniciar(Request $request)
    {
        $request->validate([
            'persona' => 'required|string',
            'vendedor_nome' => 'required|string|max:255',
            'duracao' => 'required|integer|min:60|max:600',
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
                'nome' => 'Seu Mário',
                'assistant_id' => env('VAPI_ASSISTANT_SEU_MARIO'),
            ],
            'dona_sonia' => [
                'nome' => 'Dona Sônia',
                'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
            ],
        ];

        $personaData = $personas[$simulacao->persona] ?? null;

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
