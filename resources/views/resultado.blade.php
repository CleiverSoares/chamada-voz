@extends('layout')
@section('title', 'Resultado da Simulação')
@section('content')

<div class="h-screen w-screen bg-[#060918] overflow-hidden flex flex-col">

    {{-- BG --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0 opacity-[.06]" style="background-image:linear-gradient(rgba(6,103,218,1) 1px,transparent 1px),linear-gradient(90deg,rgba(6,103,218,1) 1px,transparent 1px);background-size:40px 40px"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 70% 45% at 50% 0%,rgba(6,103,218,.18),transparent 65%)"></div>
    </div>

    {{-- CONTENT --}}
    <div class="relative z-10 flex-1 flex flex-col items-center justify-center px-8 gap-5 min-h-0 pt-14 pb-8">

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

        </div>

        {{-- DIVIDER --}}
        <div class="shrink-0 w-full max-w-5xl h-px bg-gradient-to-r from-transparent via-[#0667DA]/30 to-transparent"></div>

        {{-- FEEDBACK --}}
        <div class="w-full max-w-5xl flex gap-5 min-h-0" style="flex:1;max-height:280px">

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
        @if($simulacao->transcricao)
        <div class="shrink-0 w-full max-w-5xl flex flex-col rounded-xl border border-white/8 overflow-hidden" style="max-height:280px">
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
        <div class="shrink-0 w-full max-w-5xl flex gap-3 pb-6">
            <a href="{{ route('selecionar') }}"
               class="flex-1 group relative overflow-hidden flex items-center justify-center gap-2 rounded-xl py-3 font-black text-white text-sm uppercase tracking-widest transition-all duration-300 hover:shadow-[0_0_25px_rgba(6,103,218,.45)] hover:scale-[1.01]"
               style="background:linear-gradient(135deg,#0667DA,#3D8EF7)">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                <svg class="w-4 h-4 relative" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                </svg>
                <span class="relative">Nova Missão</span>
            </a>
            <a href="{{ route('historico') }}"
               class="flex items-center justify-center gap-2 rounded-xl px-6 py-3 font-black text-gray-400 text-sm uppercase tracking-widest border border-white/8 bg-white/3 hover:border-[#0667DA]/40 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                Histórico
            </a>
            <a href="{{ route('home') }}"
               class="flex items-center justify-center gap-2 rounded-xl px-6 py-3 font-black text-gray-400 text-sm uppercase tracking-widest border border-white/8 bg-white/3 hover:border-white/20 hover:text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                Início
            </a>
        </div>

    </div>
</div>

@endsection
