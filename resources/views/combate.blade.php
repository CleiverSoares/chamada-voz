@extends('layout')

@section('title', 'Arena de Combate')

@section('content')
<!-- COMBAT: Compact HUD - Everything fits on screen -->
<div class="h-screen w-screen bg-[#0a0e27] relative overflow-hidden flex flex-col">
    <!-- Animated Background -->
    <div class="absolute inset-0 combat-grid opacity-10"></div>
    <div class="absolute inset-0 bg-gradient-radial from-[#0667DA]/5 via-transparent to-transparent"></div>

    <!-- Top Bar -->
    <div class="relative z-10 px-4 lg:px-6 pt-20 lg:pt-24 pb-3">
        <div class="max-w-[1600px] mx-auto">
            <div class="combat-panel-compact flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 lg:gap-4">
                    <div class="flex flex-col">
                        <span class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider">Agente</span>
                        <span class="text-white text-sm lg:text-base font-black">{{ $simulacao->vendedor_nome }}</span>
                    </div>
                    <div class="w-px h-8 bg-[#0667DA]/30"></div>
                    <div class="flex flex-col">
                        <span class="text-red-500 text-[10px] font-bold uppercase tracking-wider">Alvo</span>
                        <span class="text-white text-sm lg:text-base font-black">{{ $persona['nome'] }}</span>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider">Missão</span>
                    <span class="text-white text-sm lg:text-base font-black">#{{ str_pad($simulacao->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Combat Area - Horizontal Layout -->
    <div class="relative z-10 flex-1 px-4 lg:px-6 pb-4 overflow-hidden">
        <div class="max-w-[1600px] mx-auto h-full">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 lg:gap-4 h-full">
                
                <!-- Left: Timer + Status (Compact) -->
                <div class="lg:col-span-3 flex lg:flex-col gap-3">
                    <!-- Timer -->
                    <div class="combat-panel-compact flex-1 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider mb-2">Tempo</div>
                            <div id="timer-container" class="relative inline-block">
                                <svg class="w-24 h-24 lg:w-28 lg:h-28 transform -rotate-90">
                                    <circle cx="48" cy="48" r="42" stroke="rgba(6, 103, 218, 0.2)" stroke-width="5" fill="none" class="lg:hidden"/>
                                    <circle cx="56" cy="56" r="50" stroke="rgba(6, 103, 218, 0.2)" stroke-width="6" fill="none" class="hidden lg:block"/>
                                    <circle id="timer-circle" cx="48" cy="48" r="42" stroke="#0667DA" stroke-width="5" fill="none"
                                        stroke-dasharray="263.9" stroke-dashoffset="0" stroke-linecap="round" class="transition-all duration-1000 lg:hidden"/>
                                    <circle id="timer-circle-lg" cx="56" cy="56" r="50" stroke="#0667DA" stroke-width="6" fill="none"
                                        stroke-dasharray="314.2" stroke-dashoffset="0" stroke-linecap="round" class="transition-all duration-1000 hidden lg:block"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div id="timer" class="text-2xl lg:text-3xl font-black text-white font-mono">00:00</div>
                                </div>
                            </div>
                            <div id="timer-info" class="text-[10px] text-blue-300 mt-2 uppercase tracking-wider">Aguardando</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="combat-panel-compact flex-1 flex items-center justify-center">
                        <div class="text-center w-full px-2">
                            <div class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider mb-3">Status</div>
                            <div id="status-indicator" class="flex flex-col items-center space-y-2">
                                <div class="relative">
                                    <div id="status-pulse" class="w-6 h-6 bg-gray-600 rounded-full"></div>
                                    <div id="status-ring" class="absolute inset-0 bg-gray-600 rounded-full animate-ping opacity-0"></div>
                                </div>
                                <div id="status-text" class="text-white font-bold text-xs lg:text-sm">Offline</div>
                                <div id="status-description" class="text-[10px] text-gray-400">Aguardando</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Center: Voice Visualizer + Transcript -->
                <div class="lg:col-span-6 flex flex-col gap-3 h-full overflow-hidden">
                    <!-- Voice Visualizer -->
                    <div class="combat-panel-main flex-shrink-0">
                        <div class="text-center py-4 lg:py-6">
                            <div id="voice-visualizer" class="mb-4">
                                <div class="flex items-end justify-center space-x-1 h-16 lg:h-20">
                                    @for($i = 0; $i < 20; $i++)
                                    <div class="voice-bar w-1 lg:w-1.5 bg-[#0667DA] rounded-full transition-all duration-150" style="height: 4px;"></div>
                                    @endfor
                                </div>
                            </div>
                            <div id="voice-status" class="text-lg lg:text-xl font-bold text-gray-500 mb-2">Sistema em Standby</div>
                            <div id="voice-subtitle" class="text-[10px] lg:text-xs text-gray-600">Clique em "Iniciar Chamada"</div>
                        </div>
                    </div>

                    <!-- Transcript -->
                    <div class="combat-panel-main flex flex-col min-h-0 overflow-hidden" style="flex:1 1 0">
                        <div class="flex items-center justify-between mb-2 shrink-0">
                            <div class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider">Transcrição</div>
                            <div class="flex items-center space-x-1.5">
                                <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                                <span class="text-[9px] text-gray-500 uppercase">Live</span>
                            </div>
                        </div>
                        <div id="transcript" class="transcript-container" style="flex:1 1 0;min-height:0;overflow-y:auto;max-height:100%">
                            <p class="text-gray-600 italic text-xs">Aguardando transmissão...</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Controls + Briefing -->
                <div class="lg:col-span-3 flex lg:flex-col gap-3">
                    <!-- Controls -->
                    <div class="combat-panel-compact flex-1 flex items-center justify-center">
                        <div class="flex flex-col gap-2 w-full px-2">
                            <button id="btn-start" class="combat-btn combat-btn-start">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                    <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                                </svg>
                                <span class="font-black uppercase tracking-wider text-xs">Iniciar</span>
                            </button>
                            <button id="btn-end" class="combat-btn combat-btn-end hidden">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08c-.18-.17-.29-.42-.29-.7 0-.28.11-.53.29-.71C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.18.18.29.43.29.71 0 .28-.11.53-.29.71l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.68-1.36-2.66-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                                </svg>
                                <span class="font-black uppercase tracking-wider text-xs">Encerrar</span>
                            </button>
                        </div>
                    </div>

                    <!-- Briefing -->
                    <div class="combat-panel-compact flex-1 hidden lg:flex items-center">
                        <div class="w-full px-2">
                            <div class="text-[#0667DA] text-[10px] font-bold uppercase tracking-wider mb-3">Briefing</div>
                            <div class="text-xs text-gray-300 leading-relaxed space-y-1.5">
                                <p><span class="text-[#0667DA]">▸</span> Convença o cliente</p>
                                <p><span class="text-[#0667DA]">▸</span> Use argumentos sólidos</p>
                                <p><span class="text-[#0667DA]">▸</span> Supere objeções</p>
                                <p><span class="text-yellow-500">⚠</span> Tempo limitado</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="relative w-24 h-24 mx-auto mb-6">
                <svg class="w-24 h-24 animate-spin" viewBox="0 0 50 50">
                    <circle class="opacity-25" cx="25" cy="25" r="20" stroke="#0667DA" stroke-width="4" fill="none"></circle>
                    <circle class="opacity-75" cx="25" cy="25" r="20" stroke="#0667DA" stroke-width="4" fill="none" stroke-dasharray="80" stroke-dashoffset="60" stroke-linecap="round"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                    </svg>
                </div>
            </div>
            <div id="loading-text" class="text-xl font-black text-white mb-2">Conectando...</div>
            <div id="loading-subtitle" class="text-xs text-blue-300">Estabelecendo conexão</div>
            <div class="flex justify-center space-x-2 mt-4">
                <div class="w-2 h-2 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-2 h-2 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-2 h-2 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        </div>
    </div>
</div>

<style>
.combat-grid {
    background-image: 
        linear-gradient(rgba(6, 103, 218, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(6, 103, 218, 0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    animation: grid-move 15s linear infinite;
}

@keyframes grid-move {
    0% { background-position: 0 0; }
    100% { background-position: 40px 40px; }
}

.bg-gradient-radial {
    background: radial-gradient(circle at center, var(--tw-gradient-stops));
}

.combat-panel-compact {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.08) 0%, rgba(10, 14, 39, 0.85) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 0.75rem;
    padding: 0.75rem;
    backdrop-filter: blur(10px);
}

.combat-panel-main {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.05) 0%, rgba(10, 14, 39, 0.9) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 0.75rem;
    padding: 1rem;
    backdrop-filter: blur(10px);
}

.transcript-container {
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(6, 103, 218, 0.2);
    border-radius: 0.5rem;
    padding: 0.75rem;
    overflow-y: auto;
    font-family: 'Courier New', monospace;
    font-size: 0.75rem;
    line-height: 1.5;
}

.transcript-container::-webkit-scrollbar {
    width: 4px;
}

.transcript-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.3);
}

.transcript-container::-webkit-scrollbar-thumb {
    background: rgba(6, 103, 218, 0.5);
    border-radius: 4px;
}

.voice-bar {
    animation: voice-idle 2s ease-in-out infinite;
}

.voice-bar:nth-child(odd) { animation-delay: 0.1s; }
.voice-bar:nth-child(even) { animation-delay: 0.2s; }

@keyframes voice-idle {
    0%, 100% { height: 4px; opacity: 0.3; }
    50% { height: 10px; opacity: 0.6; }
}

.combat-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 700;
    transition: all 0.3s ease;
    border: 2px solid;
}

.combat-btn-start {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #34d399;
    color: white;
}

.combat-btn-start:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.5);
}

.combat-btn-end {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #f87171;
    color: white;
}

.combat-btn-end:hover {
    transform: scale(1.05);
    box-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
}
</style>

@endsection

@section('scripts')
<script>
    window.vapiConfig = {
        publicKey: "{{ $vapiPublicKey }}",
        assistantId: "{{ $persona['assistant_id'] }}",
        simulacaoId: {{ $simulacao->id }},
        duracaoMaxima: {{ $duracaoMaxima }}
    };
</script>
@vite('resources/js/vapi-combate.js')
@endsection
