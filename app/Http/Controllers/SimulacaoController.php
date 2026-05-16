<?php

namespace App\Http\Controllers;

use App\Models\Simulacao;
use Illuminate\Http\Request;

class SimulacaoController extends Controller
{
    public static function nomePersona(string $key): string
    {
        $p = \App\Models\Persona::where('chave', $key)->first();
        if ($p) return $p->nome;

        return match($key) {
            'seu_mario'              => 'Antônio',
            'dona_sonia'             => 'Sônia',
            'flavio_academia_vendas' => 'Flávio — Mentor Elite',
            default                  => ucwords(str_replace('_', ' ', $key)),
        };
    }

    public static function emojiPersona(string $key): string
    {
        $p = \App\Models\Persona::where('chave', $key)->first();
        if ($p) return $p->emoji;

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
        $personasCollection = \App\Models\Persona::all();
        $personas = [];
        
        foreach ($personasCollection as $p) {
            $personas[$p->chave] = [
                'id' => $p->id,
                'nome' => $p->nome,
                'descricao' => $p->descricao,
                'dificuldade' => $p->dificuldade,
                'assistant_id' => $p->assistant_id,
                'emoji' => $p->emoji,
            ];
        }

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

        $p = \App\Models\Persona::where('chave', $simulacao->persona)->first();

        $personaData = [
            'nome' => $p ? $p->nome : ucfirst(str_replace('_', ' ', $simulacao->persona)),
            'assistant_id' => $p ? $p->assistant_id : null,
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
        
        $grafico_evolucao = Simulacao::where('status', 'concluido')
            ->orderBy('created_at', 'asc')
            ->take(30)
            ->get(['score', 'created_at', 'vendedor_nome'])
            ->map(function($sim) {
                return [
                    'score' => $sim->score,
                    'data' => $sim->created_at->format('d/m'),
                ];
            });

        // 1. GAMIFICAÇÃO: Ranking Global com Patentes (XP = soma de todos os scores)
        $rankingData = Simulacao::select('vendedor_nome')
            ->selectRaw('MAX(score) as max_score')
            ->selectRaw('SUM(score) as total_xp')
            ->selectRaw('COUNT(*) as total_missoes')
            ->where('status', 'concluido')
            ->groupBy('vendedor_nome')
            ->orderByDesc('total_xp')
            ->limit(10)
            ->get();
            
        $ranking = $rankingData->map(function($r) {
            $xp = $r->total_xp;
            if ($xp < 500) $patente = 'Recruta';
            elseif ($xp < 1500) $patente = 'Soldado de Linha';
            elseif ($xp < 3000) $patente = 'Combatente Tático';
            elseif ($xp < 5000) $patente = 'Forças Especiais';
            else $patente = 'Lenda da Arena';
            
            $r->patente = $patente;
            return $r;
        });
        
        return view('historico', compact('simulacoes', 'estatisticas', 'grafico_evolucao', 'ranking'));
    }
}
