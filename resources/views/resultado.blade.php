@extends('layout')

@section('title', 'Resultado da Simulação')

@section('content')
<!-- RESULTS: Achievement Unlock Screen -->
<div class="min-h-screen bg-gradient-to-b from-black via-[#0a0e27] to-black py-8 sm:py-12 px-4 sm:px-6 relative overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(6,103,218,0.1),transparent_50%)]"></div>
        @if($simulacao->score >= 80)
        <div class="absolute inset-0 victory-rays"></div>
        @endif
    </div>

    <div class="relative z-10 max-w-6xl mx-auto">
        <!-- Mission Complete Header -->
        <div class="text-center mb-8 sm:mb-12 mission-complete-animation">
            <div class="inline-block mb-4 sm:mb-6">
                <div class="text-[#0667DA] text-xs sm:text-sm font-bold tracking-[0.2em] sm:tracking-[0.3em] uppercase mb-2">Missão Concluída</div>
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black text-white mb-3 tracking-tight">
                    @if($simulacao->score >= 80)
                        <span class="text-green-400">VITÓRIA</span>
                    @elseif($simulacao->score >= 60)
                        <span class="text-yellow-400">APROVADO</span>
                    @else
                        <span class="text-orange-400">TREINAMENTO</span>
                    @endif
                </h1>
                <div class="h-1 w-32 bg-gradient-to-r from-transparent via-[#0667DA] to-transparent mx-auto"></div>
            </div>
            <p class="text-base sm:text-lg md:text-xl text-blue-200">{{ $simulacao->vendedor_nome }} <span class="text-gray-500">vs</span> {{ ucfirst(str_replace('_', ' ', $simulacao->persona)) }}</p>
        </div>

        <!-- Score Display - Large Central -->
        <div class="mb-8 sm:mb-12 score-reveal-animation">
            <div class="relative max-w-md mx-auto">
                <!-- Glow Effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-[#0667DA]/30 to-transparent blur-3xl"></div>
                
                <!-- Score Card -->
                <div class="relative bg-gradient-to-br from-[#0d1235] to-[#0a0e27] border-2 border-[#0667DA] rounded-2xl sm:rounded-3xl p-6 sm:p-12 text-center">
                    <div class="text-[#0667DA] text-xs sm:text-sm font-bold uppercase tracking-wider mb-3 sm:mb-4">Pontuação Final</div>
                    
                    <!-- Circular Progress -->
                    <div class="relative w-48 h-48 sm:w-64 sm:h-64 mx-auto mb-4 sm:mb-6">
                        <svg class="transform -rotate-90 w-48 h-48 sm:w-64 sm:h-64">
                            <circle cx="96" cy="96" r="88" stroke="rgba(6, 103, 218, 0.2)" stroke-width="10" fill="none" class="sm:hidden" />
                            <circle cx="96" cy="96" r="88"
                                stroke="{{ $simulacao->score >= 80 ? '#10B981' : ($simulacao->score >= 60 ? '#F59E0B' : '#F97316') }}" 
                                stroke-width="10" 
                                fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 88 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $simulacao->score / 100) }}"
                                stroke-linecap="round"
                                class="score-circle-animation sm:hidden" />
                            <circle cx="128" cy="128" r="120" stroke="rgba(6, 103, 218, 0.2)" stroke-width="12" fill="none" class="hidden sm:block" />
                            <circle cx="128" cy="128" r="120" 
                                stroke="{{ $simulacao->score >= 80 ? '#10B981' : ($simulacao->score >= 60 ? '#F59E0B' : '#F97316') }}" 
                                stroke-width="12" 
                                fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 120 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 120 * (1 - $simulacao->score / 100) }}"
                                stroke-linecap="round"
                                class="score-circle-animation hidden sm:block" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div>
                                <div class="text-6xl sm:text-8xl font-black text-white score-number-animation">{{ $simulacao->score }}</div>
                                <div class="text-xl sm:text-2xl text-gray-400 font-bold">/100</div>
                            </div>
                        </div>
                    </div>

                    <!-- Rank Badge -->
                    <div class="inline-flex items-center space-x-2 sm:space-x-3 px-4 sm:px-6 py-2 sm:py-3 rounded-full text-base sm:text-xl font-black
                        {{ $simulacao->score >= 80 ? 'bg-green-500/20 text-green-400 border-2 border-green-500' : 
                           ($simulacao->score >= 60 ? 'bg-yellow-500/20 text-yellow-400 border-2 border-yellow-500' : 
                           'bg-orange-500/20 text-orange-400 border-2 border-orange-500') }}">
                        @if($simulacao->score >= 80)
                            <span class="text-2xl sm:text-3xl">🏆</span>
                            <span>MESTRE</span>
                        @elseif($simulacao->score >= 60)
                            <span class="text-2xl sm:text-3xl">⭐</span>
                            <span>COMPETENTE</span>
                        @else
                            <span class="text-2xl sm:text-3xl">💪</span>
                            <span>EM EVOLUÇÃO</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Cards -->
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-6 mb-8 sm:mb-12">
            <!-- Positive Points -->
            <div class="feedback-card-animation" style="animation-delay: 0.2s">
                <div class="bg-gradient-to-br from-green-900/30 to-[#0a0e27] border-2 border-green-500/50 rounded-xl sm:rounded-2xl p-4 sm:p-8 h-full">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-2xl font-black text-green-400 uppercase tracking-tight">Acertos</h3>
                    </div>
                    <div class="text-gray-300 leading-relaxed text-sm sm:text-base">
                        {{ $simulacao->pontos_positivos ?? 'Análise em processamento...' }}
                    </div>
                </div>
            </div>

            <!-- Improvement Points -->
            <div class="feedback-card-animation" style="animation-delay: 0.4s">
                <div class="bg-gradient-to-br from-blue-900/30 to-[#0a0e27] border-2 border-[#0667DA]/50 rounded-xl sm:rounded-2xl p-4 sm:p-8 h-full">
                    <div class="flex items-center space-x-2 sm:space-x-3 mb-4 sm:mb-6">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-[#0667DA]/20 rounded-lg sm:rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 sm:w-7 sm:h-7 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-2xl font-black text-[#0667DA] uppercase tracking-tight">Melhorias</h3>
                    </div>
                    <div class="text-gray-300 leading-relaxed text-sm sm:text-base">
                        {{ $simulacao->pontos_melhoria ?? 'Análise em processamento...' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission Stats -->
        <div class="grid grid-cols-3 gap-3 sm:gap-6 mb-8 sm:mb-12">
            <div class="stat-card-animation" style="animation-delay: 0.6s">
                <div class="bg-[#0d1235] border border-[#0667DA]/30 rounded-lg sm:rounded-xl p-3 sm:p-6 text-center">
                    <div class="text-[#0667DA] text-[9px] sm:text-xs font-bold uppercase tracking-wider mb-1 sm:mb-2">Duração</div>
                    <div class="text-2xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1">{{ gmdate('i:s', $simulacao->duracao_segundos ?? 0) }}</div>
                    <div class="text-[10px] sm:text-sm text-gray-500">minutos</div>
                </div>
            </div>
            <div class="stat-card-animation" style="animation-delay: 0.7s">
                <div class="bg-[#0d1235] border border-[#0667DA]/30 rounded-lg sm:rounded-xl p-3 sm:p-6 text-center">
                    <div class="text-[#0667DA] text-[9px] sm:text-xs font-bold uppercase tracking-wider mb-1 sm:mb-2">Data</div>
                    <div class="text-2xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1">{{ $simulacao->created_at->format('d/m') }}</div>
                    <div class="text-[10px] sm:text-sm text-gray-500">{{ $simulacao->created_at->format('Y') }}</div>
                </div>
            </div>
            <div class="stat-card-animation" style="animation-delay: 0.8s">
                <div class="bg-[#0d1235] border border-[#0667DA]/30 rounded-lg sm:rounded-xl p-3 sm:p-6 text-center">
                    <div class="text-[#0667DA] text-[9px] sm:text-xs font-bold uppercase tracking-wider mb-1 sm:mb-2">Horário</div>
                    <div class="text-2xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1">{{ $simulacao->created_at->timezone('America/Sao_Paulo')->format('H:i') }}</div>
                    <div class="text-[10px] sm:text-sm text-gray-500">Brasília</div>
                </div>
            </div>
        </div>

        <!-- Transcript (if available) -->
        @if($simulacao->transcricao)
        <div class="mb-12 transcript-animation">
            <div class="bg-[#0d1235] border border-[#0667DA]/30 rounded-2xl p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <svg class="w-6 h-6 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight">Transcrição Completa</h3>
                </div>
                <div class="bg-black/50 rounded-xl p-6 max-h-96 overflow-y-auto border border-[#0667DA]/20">
                    <pre class="whitespace-pre-wrap text-gray-300 leading-relaxed text-sm font-mono">{{ $simulacao->transcricao }}</pre>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-3 sm:gap-4">
            <a href="{{ route('selecionar') }}" class="action-btn action-btn-primary w-full sm:w-auto">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                </svg>
                <span class="font-black uppercase tracking-wider text-sm sm:text-base">Nova Missão</span>
            </a>
            <a href="{{ route('historico') }}" class="action-btn action-btn-secondary w-full sm:w-auto">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                <span class="font-black uppercase tracking-wider text-sm sm:text-base">Ver Histórico</span>
            </a>
            <a href="{{ route('home') }}" class="action-btn action-btn-tertiary w-full sm:w-auto">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <span class="font-black uppercase tracking-wider text-sm sm:text-base">Início</span>
            </a>
        </div>
    </div>
</div>

<style>
.victory-rays {
    background: repeating-conic-gradient(from 0deg at 50% 50%, 
        transparent 0deg, 
        rgba(16, 185, 129, 0.1) 5deg, 
        transparent 10deg);
    animation: victory-rotate 20s linear infinite;
}

@keyframes victory-rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.mission-complete-animation {
    animation: mission-complete 1s ease-out;
}

@keyframes mission-complete {
    0% {
        opacity: 0;
        transform: scale(0.8) translateY(-50px);
    }
    60% {
        transform: scale(1.05) translateY(0);
    }
    100% {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.score-reveal-animation {
    animation: score-reveal 1.2s ease-out 0.3s backwards;
}

@keyframes score-reveal {
    0% {
        opacity: 0;
        transform: scale(0.5);
    }
    70% {
        transform: scale(1.1);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.score-circle-animation {
    animation: score-circle 2s ease-out 0.5s backwards;
}

@keyframes score-circle {
    0% {
        stroke-dashoffset: {{ 2 * 3.14159 * 120 }};
    }
    100% {
        stroke-dashoffset: {{ 2 * 3.14159 * 120 * (1 - $simulacao->score / 100) }};
    }
}

.score-number-animation {
    animation: score-number 1.5s ease-out 0.8s backwards;
}

@keyframes score-number {
    0% {
        opacity: 0;
        transform: scale(0);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.feedback-card-animation {
    animation: slide-in-up 0.8s ease-out backwards;
}

@keyframes slide-in-up {
    0% {
        opacity: 0;
        transform: translateY(50px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card-animation {
    animation: fade-in-scale 0.6s ease-out backwards;
}

@keyframes fade-in-scale {
    0% {
        opacity: 0;
        transform: scale(0.8);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.transcript-animation {
    animation: fade-in 1s ease-out 1s backwards;
}

@keyframes fade-in {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 2rem;
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    border: 2px solid;
}

.action-btn-primary {
    background: linear-gradient(135deg, #0667DA 0%, #3D8EF7 100%);
    border-color: #3D8EF7;
    color: white;
}

.action-btn-primary:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(6, 103, 218, 0.6);
}

.action-btn-secondary {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.2) 0%, rgba(10, 14, 39, 0.8) 100%);
    border-color: rgba(6, 103, 218, 0.5);
    color: white;
}

.action-btn-secondary:hover {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.3) 0%, rgba(10, 14, 39, 0.9) 100%);
    border-color: #0667DA;
    transform: scale(1.05);
}

.action-btn-tertiary {
    background: transparent;
    border-color: rgba(255, 255, 255, 0.2);
    color: white;
}

.action-btn-tertiary:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.4);
    transform: scale(1.05);
}
</style>
@endsection
