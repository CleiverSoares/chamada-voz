@extends('layout')
@section('title', 'Resultado da Simulação')
@section('content')

<div class="h-screen w-screen bg-[#060918] overflow-hidden flex flex-col">

    {{-- BG --}}
    <div class="absolute inset-0 pointer-events-none print:hidden">
        <div class="absolute inset-0 opacity-[.06]" style="background-image:linear-gradient(rgba(6,103,218,1) 1px,transparent 1px),linear-gradient(90deg,rgba(6,103,218,1) 1px,transparent 1px);background-size:40px 40px"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 70% 45% at 50% 0%,rgba(6,103,218,.18),transparent 65%)"></div>
    </div>

    <style>
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body { background: #ffffff !important; margin: 0; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .print\:hidden { display: none !important; }
            .print\:flex { display: flex !important; }
            .print\:block { display: block !important; }
            /* Esconde as coisas do dashboard na impressão */
            .dashboard-content, nav, .fixed { display: none !important; }
        }
    </style>

    {{-- CERTIFICADO A4 (SÓ VISÍVEL NA IMPRESSÃO) --}}
    <div class="hidden print:flex flex-col w-[297mm] h-[210mm] bg-white relative p-12 box-border mx-auto border-[15px] border-[#0667DA] outline outline-[3px] outline-offset-[-20px] outline-[#0667DA]/30">
        {{-- Marcas D'água --}}
        <div class="absolute inset-0 opacity-[0.03] bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-[#0667DA] via-transparent to-transparent"></div>
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b-2 border-[#0667DA]/20 pb-6 mb-10 z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-[#0667DA]/10 rounded-xl flex items-center justify-center border border-[#0667DA]/20">
                    <img src="{{ asset('images/favicon-2.png') }}" alt="Logo" class="w-12 h-12 object-contain">
                </div>
                <div>
                    <h2 class="text-3xl font-black text-[#000000] tracking-tighter">ALTERDATA <span class="text-[#0667DA]">SOFTWARE</span></h2>
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-[0.3em]">Academia de Vendas Avançadas</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-gray-400 font-bold text-sm">Registro Oficial</div>
                <div class="text-black font-black text-xl">#{{ str_pad($simulacao->id, 5, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        {{-- Corpo do Certificado --}}
        <div class="flex-1 flex flex-col items-center justify-center text-center z-10">
            <h1 class="text-5xl font-black text-gray-900 uppercase tracking-widest mb-8">Certificado de Desempenho</h1>
            
            <p class="text-xl text-gray-600 mb-6">Certificamos que o(a) agente tático(a)</p>
            <h2 class="text-5xl font-black text-[#0667DA] border-b-4 border-emerald-500 px-8 pb-2 mb-8">{{ $simulacao->vendedor_nome }}</h2>
            
            <p class="text-xl text-gray-600 mb-8 max-w-3xl leading-relaxed">
                Concluiu com sucesso a simulação de combate em vendas enfrentando o adversário <strong class="text-gray-900">{{ \App\Http\Controllers\SimulacaoController::nomePersona($simulacao->persona) }}</strong> no dia <strong class="text-gray-900">{{ $simulacao->created_at->format('d/m/Y') }}</strong>, demonstrando suas habilidades e técnicas comerciais.
            </p>

            <div class="flex items-center justify-center gap-12 mt-4 bg-gray-50 rounded-2xl px-12 py-6 border border-gray-200 shadow-sm">
                <div class="text-center">
                    <div class="text-gray-400 text-xs font-black uppercase tracking-widest mb-2">Score Final</div>
                    <div class="text-6xl font-black {{ $simulacao->score >= 80 ? 'text-emerald-500' : ($simulacao->score >= 60 ? 'text-yellow-500' : 'text-orange-500') }}">{{ $simulacao->score }}</div>
                </div>
                <div class="w-px h-16 bg-gray-300"></div>
                <div class="text-left flex flex-col gap-2">
                    <div class="text-sm font-bold text-gray-800 flex justify-between gap-8"><span>Empatia:</span> <span class="text-[#0667DA]">{{ $simulacao->score_empatia ?? '-' }}</span></div>
                    <div class="text-sm font-bold text-gray-800 flex justify-between gap-8"><span>Conhecimento:</span> <span class="text-[#0667DA]">{{ $simulacao->score_conhecimento ?? '-' }}</span></div>
                    <div class="text-sm font-bold text-gray-800 flex justify-between gap-8"><span>Objeções:</span> <span class="text-[#0667DA]">{{ $simulacao->score_objecao ?? '-' }}</span></div>
                    <div class="text-sm font-bold text-gray-800 flex justify-between gap-8"><span>Fechamento:</span> <span class="text-[#0667DA]">{{ $simulacao->score_fechamento ?? '-' }}</span></div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-12 pt-8 flex items-end justify-between z-10">
            <div class="text-left">
                <div class="text-sm text-gray-500 font-bold">Autenticação VAPI IA</div>
                <div class="text-xs text-gray-400 font-mono">{{ md5($simulacao->id . $simulacao->created_at) }}</div>
            </div>
            <div class="text-center">
                <div class="w-64 h-px bg-gray-400 mb-2"></div>
                <div class="text-sm font-bold text-gray-800 uppercase tracking-widest">Inteligência Artificial</div>
                <div class="text-xs text-gray-500">Diretoria de Simulação</div>
            </div>
        </div>
    </div>

    {{-- CONTENT ORIGINAL DO DASHBOARD (Oculto na impressão) --}}
    <div class="relative z-10 flex-1 overflow-y-auto px-8 pt-10 pb-8 print:hidden">
        <div class="max-w-5xl mx-auto flex flex-col items-center gap-6">

            {{-- TOP: score + title --}}
        <div class="shrink-0 flex items-center gap-8">

            {{-- circular score --}}
            <div class="relative w-28 h-28">
                <svg class="w-28 h-28 -rotate-90">
                    <circle cx="56" cy="56" r="48" stroke="rgba(255,255,255,.06)" stroke-width="8" fill="none"/>
                    <circle cx="56" cy="56" r="48"
                        stroke="{{ $simulacao->score >= 80 ? '#10B981' : ($simulacao->score >= 60 ? '#F59E0B' : '#F97316') }}"
                        stroke-width="8" fill="none"
                        stroke-dasharray="{{ round(2 * 3.14159 * 48) }}"
                        stroke-dashoffset="{{ round(2 * 3.14159 * 48 * (1 - $simulacao->score / 100)) }}"
                        stroke-linecap="round"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-black text-white leading-none">{{ $simulacao->score }}</span>
                    <span class="text-gray-600 text-xs">/100</span>
                </div>
            </div>

            {{-- title block --}}
            <div>
                <p class="text-[#0667DA] text-[10px] font-black uppercase tracking-[.3em] mb-1">— Missão Concluída —</p>
                <h1 class="text-5xl font-black leading-none tracking-tight
                    {{ $simulacao->score >= 80 ? 'text-emerald-400' : ($simulacao->score >= 60 ? 'text-yellow-400' : 'text-orange-400') }}">
                    {{ $simulacao->score >= 80 ? 'VITÓRIA' : ($simulacao->score >= 60 ? 'APROVADO' : 'TREINAMENTO') }}
                </h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-white font-bold text-sm">{{ $simulacao->vendedor_nome }}</span>
                    <span class="text-gray-700 text-xs">vs</span>
                    <span class="text-[#0667DA] font-bold text-sm">{{ \App\Http\Controllers\SimulacaoController::nomePersona($simulacao->persona) }}</span>
                    <span class="w-px h-3 bg-white/10"></span>
                    <span class="text-gray-600 text-xs">{{ gmdate('i:s', $simulacao->duracao_segundos ?? 0) }}</span>
                    <span class="w-px h-3 bg-white/10"></span>
                    <span class="text-gray-600 text-xs">{{ $simulacao->created_at->format('d/m H:i') }}</span>
                    <span class="w-px h-3 bg-white/10"></span>
                    <span class="text-xs font-black px-2 py-0.5 rounded-full border
                        {{ $simulacao->score >= 80 ? 'text-emerald-400 border-emerald-500/40 bg-emerald-500/10' : ($simulacao->score >= 60 ? 'text-yellow-400 border-yellow-500/40 bg-yellow-500/10' : 'text-orange-400 border-orange-500/40 bg-orange-500/10') }}">
                        {{ $simulacao->score >= 80 ? '🏆 Mestre' : ($simulacao->score >= 60 ? '⭐ Competente' : '💪 Em Evolução') }}
                    </span>
                </div>
            </div>

            {{-- radar chart simulado --}}
            <div class="relative w-48 h-48 ml-8">
                <canvas id="radarChart"></canvas>
            </div>

        </div>

        {{-- DIVIDER --}}
        <div class="shrink-0 w-full max-w-5xl h-px bg-gradient-to-r from-transparent via-[#0667DA]/30 to-transparent"></div>

        {{-- AUDIO PLAYER --}}
        @if($simulacao->recording_url)
        <div class="shrink-0 w-full max-w-5xl flex items-center gap-4 p-4 rounded-xl border border-white/5 bg-white/5 backdrop-blur-sm">
            <div class="w-10 h-10 rounded-full flex items-center justify-center bg-[#0667DA]/20 text-[#0667DA] shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28a4.39 4.39 0 00-1.5-.28C8.01 12 6 14.01 6 16.5S8.01 21 10.5 21c2.31 0 4.2-1.75 4.45-4H15V6h4V3h-7z"/></svg>
            </div>
            <div class="flex-1 flex flex-col gap-1">
                <span class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Gravação do Combate</span>
                <audio id="audioPlayer" controls class="w-full h-8 outline-none" src="{{ $simulacao->recording_url }}">
                    Seu navegador não suporta o elemento de áudio.
                </audio>
            </div>
        </div>
        @endif

        {{-- FEEDBACK --}}
        <div class="w-full max-w-5xl flex gap-5">

            {{-- acertos --}}
            <div class="flex-1 flex flex-col rounded-xl border border-emerald-500/20 bg-emerald-500/5 overflow-hidden">
                <div class="shrink-0 flex items-center gap-2 px-4 py-2.5 border-b border-emerald-500/15">
                    <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                    <span class="text-emerald-400 text-[10px] font-black uppercase tracking-widest">Acertos</span>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-3">
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $simulacao->pontos_positivos ?? 'Análise em processamento...' }}</p>
                </div>
            </div>

            {{-- melhorias --}}
            <div class="flex-1 flex flex-col rounded-xl border border-[#0667DA]/20 bg-[#0667DA]/5 overflow-hidden">
                <div class="shrink-0 flex items-center gap-2 px-4 py-2.5 border-b border-[#0667DA]/15">
                    <svg class="w-3.5 h-3.5 text-[#0667DA] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    <span class="text-[#0667DA] text-[10px] font-black uppercase tracking-widest">Melhorias</span>
                </div>
                <div class="flex-1 overflow-y-auto px-4 py-3">
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $simulacao->pontos_melhoria ?? 'Análise em processamento...' }}</p>
                </div>
            </div>

        </div>

        {{-- TRANSCRIÇÃO --}}
        @if(!empty($simulacao->transcricao_json) && $simulacao->recording_url)
            @php
                $messages = json_decode($simulacao->transcricao_json, true) ?? [];
                $firstTime = !empty($messages) ? ($messages[0]['time'] ?? 0) : 0;
            @endphp
            <div class="shrink-0 w-full max-w-5xl flex flex-col rounded-xl border border-white/8 bg-[#060918]/50 overflow-hidden">
                <div class="shrink-0 flex items-center justify-between px-4 py-3 border-b border-white/8 bg-white/5">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span class="text-white text-xs font-black uppercase tracking-widest">Transcrição Interativa</span>
                    </div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Clique nas mensagens para pular o áudio</span>
                </div>
                <div class="overflow-y-auto p-6 flex flex-col gap-4">
                    @foreach($messages as $msg)
                        @continue(!isset($msg['role']) || !isset($msg['message']) || $msg['role'] === 'system')
                        @php
                            $msgTime = $msg['time'] ?? $firstTime;
                            $seconds = max(0, ($msgTime - $firstTime) / 1000);
                            $isUser = $msg['role'] === 'user';
                            
                            // Analise de Sentimento Simples (MOCK Inteligente)
                            $messageText = mb_strtolower($msg['message']);
                            $sentimentClass = '';
                            $sentimentIcon = '';
                            if (!$isUser) {
                                if (str_contains($messageText, 'não') || str_contains($messageText, 'caro') || str_contains($messageText, 'ruim') || str_contains($messageText, 'difícil')) {
                                    $sentimentClass = 'border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.1)]';
                                    $sentimentIcon = '<span class="text-red-400" title="Irritado/Resistente">😡</span>';
                                } elseif (str_contains($messageText, 'bom') || str_contains($messageText, 'gostei') || str_contains($messageText, 'fechado') || str_contains($messageText, 'ótimo')) {
                                    $sentimentClass = 'border-emerald-500/50 shadow-[0_0_15px_rgba(16,185,129,0.1)]';
                                    $sentimentIcon = '<span class="text-emerald-400" title="Propenso à compra">🤩</span>';
                                } else {
                                    $sentimentClass = 'border-yellow-500/30';
                                    $sentimentIcon = '<span class="text-yellow-400" title="Neutro">😐</span>';
                                }
                            }
                        @endphp
                        <div class="flex {{ $isUser ? 'justify-end' : 'justify-start' }}">
                            <div onclick="document.getElementById('audioPlayer').currentTime = {{ $seconds }}; document.getElementById('audioPlayer').play()"
                                 class="cursor-pointer transition-all hover:scale-[1.02] max-w-[80%] rounded-2xl px-5 py-3 text-sm leading-relaxed relative group
                                 {{ $isUser ? 'bg-gradient-to-br from-[#0667DA] to-[#3D8EF7] text-white rounded-br-sm shadow-lg shadow-[#0667DA]/20 border border-[#3D8EF7]' : 'bg-[#0d1235] text-gray-200 border-2 rounded-bl-sm ' . $sentimentClass }}">
                                
                                <div class="text-[9px] font-black uppercase mb-1.5 {{ $isUser ? 'text-white/70' : 'text-gray-500' }} flex justify-between items-center gap-6">
                                    <span class="flex items-center gap-1">
                                        {{ $isUser ? $simulacao->vendedor_nome : \App\Http\Controllers\SimulacaoController::nomePersona($simulacao->persona) }}
                                        @if(!$isUser) {!! $sentimentIcon !!} @endif
                                    </span>
                                    <span class="font-mono">{{ gmdate('i:s', $seconds) }}</span>
                                </div>
                                <p>{{ $msg['message'] }}</p>
                                
                                {{-- Hover play icon --}}
                                <div class="absolute inset-0 bg-black/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center {{ $isUser ? 'rounded-br-sm' : 'rounded-bl-sm' }}">
                                    <svg class="w-8 h-8 text-white drop-shadow-md" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($simulacao->transcricao)
        <div class="shrink-0 w-full max-w-5xl flex flex-col rounded-xl border border-white/8 overflow-hidden">
            <div class="shrink-0 flex items-center gap-2 px-4 py-2 border-b border-white/8">
                <svg class="w-3 h-3 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                </svg>
                <span class="text-gray-600 text-[10px] font-black uppercase tracking-widest">Transcrição</span>
            </div>
            <div class="overflow-y-auto px-4 py-2.5">
                <pre class="whitespace-pre-wrap text-gray-600 text-xs leading-relaxed font-mono">{{ $simulacao->transcricao }}</pre>
            </div>
        </div>
        @endif

        {{-- ACTIONS --}}
        <div class="shrink-0 w-full max-w-5xl flex gap-3 pb-6 print:hidden">
            <a href="{{ route('selecionar') }}"
               class="flex-1 group relative overflow-hidden flex items-center justify-center gap-2 rounded-xl py-3 font-black text-white text-sm uppercase tracking-widest transition-all duration-300 hover:shadow-[0_0_25px_rgba(6,103,218,.45)] hover:scale-[1.01]"
               style="background:linear-gradient(135deg,#0667DA,#3D8EF7)">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                <svg class="w-4 h-4 relative" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                </svg>
                <span class="relative">Nova Missão</span>
            </a>
            
            <button onclick="window.print()"
               class="flex items-center justify-center gap-2 rounded-xl px-6 py-3 font-black text-gray-400 text-sm uppercase tracking-widest border border-white/8 bg-white/3 hover:border-emerald-500/40 hover:text-emerald-400 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Gerar PDF
            </button>

            <a href="{{ route('historico') }}"
               class="flex items-center justify-center gap-2 rounded-xl px-6 py-3 font-black text-gray-400 text-sm uppercase tracking-widest border border-white/8 bg-white/3 hover:border-[#0667DA]/40 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                Histórico
            </a>
        </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('radarChart').getContext('2d');
        const scoreBase = {{ $simulacao->score }};
        
        const d1 = {{ $simulacao->score_empatia ?? 'scoreBase' }}; // Empatia
        const d2 = {{ $simulacao->score_conhecimento ?? 'scoreBase' }}; // Conhecimento
        const d3 = {{ $simulacao->score_objecao ?? 'scoreBase' }}; // Objeção
        const d4 = {{ $simulacao->score_fechamento ?? 'scoreBase' }}; // Fechamento

        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Empatia', 'Produto', 'Objeções', 'Fechamento'],
                datasets: [{
                    label: 'Score',
                    data: [d1, d2, d3, d4],
                    backgroundColor: 'rgba(6, 103, 218, 0.2)',
                    borderColor: '#3D8EF7',
                    pointBackgroundColor: '#0667DA',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#0667DA',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255, 255, 255, 0.1)' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' },
                        pointLabels: { color: 'rgba(255, 255, 255, 0.7)', font: { size: 9, weight: 'bold' } },
                        ticks: { display: false, min: 0, max: 100 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>

@endsection
