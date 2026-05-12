@extends('layout')

@section('title', 'Arena de Combate')

@section('content')
<!-- COMBAT: HUD Interface with Real-time Status -->
<div class="min-h-screen bg-[#0a0e27] relative">
    <!-- Animated Radar Background -->
    <div class="fixed inset-0 radar-bg opacity-20 pointer-events-none -z-10"></div>
    <div class="fixed inset-0 bg-gradient-radial from-[#0667DA]/10 via-transparent to-[#0a0e27] pointer-events-none -z-10"></div>

    <!-- HUD Container -->
    <div class="relative z-10 p-4 md:p-6 pt-24 pb-8 space-y-4 md:space-y-6 max-w-7xl mx-auto">
        <!-- Top HUD Bar -->
        <div class="hud-panel">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 w-full md:w-auto">
                    <div class="hud-badge">
                        <span class="text-[#0667DA] text-xs font-bold uppercase tracking-wider">Agente</span>
                        <span class="text-white text-base md:text-lg font-black">{{ $simulacao->vendedor_nome }}</span>
                    </div>
                    <div class="hidden sm:block w-px h-12 bg-[#0667DA]/30"></div>
                    <div class="hud-badge">
                        <span class="text-red-500 text-xs font-bold uppercase tracking-wider">Alvo</span>
                        <span class="text-white text-base md:text-lg font-black">{{ $persona['nome'] }}</span>
                    </div>
                </div>
                <div class="hud-badge">
                    <span class="text-[#0667DA] text-xs font-bold uppercase tracking-wider">ID Missão</span>
                    <span class="text-white text-base md:text-lg font-black">#{{ str_pad($simulacao->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
        </div>

        <!-- Main Combat Area -->
        <div class="grid lg:grid-cols-3 gap-4 md:gap-6">
            <!-- Left Panel: Status -->
            <div class="lg:col-span-1 space-y-4 md:space-y-6">
                <!-- Timer Display -->
                <div class="hud-panel">
                    <div class="text-center">
                        <div class="text-[#0667DA] text-xs font-bold uppercase tracking-wider mb-3">Tempo Restante</div>
                        <div id="timer-container" class="relative inline-block">
                            <svg class="w-32 h-32 md:w-40 md:h-40 transform -rotate-90">
                                <circle cx="64" cy="64" r="56" stroke="rgba(6, 103, 218, 0.2)" stroke-width="6" fill="none" class="md:hidden"/>
                                <circle cx="80" cy="80" r="70" stroke="rgba(6, 103, 218, 0.2)" stroke-width="8" fill="none" class="hidden md:block"/>
                                <circle id="timer-circle" cx="64" cy="64" r="56" stroke="#0667DA" stroke-width="6" fill="none"
                                    stroke-dasharray="351.9" stroke-dashoffset="0" stroke-linecap="round"
                                    class="transition-all duration-1000 md:hidden"/>
                                <circle id="timer-circle-lg" cx="80" cy="80" r="70" stroke="#0667DA" stroke-width="8" fill="none"
                                    stroke-dasharray="439.8" stroke-dashoffset="0" stroke-linecap="round"
                                    class="transition-all duration-1000 hidden md:block"/>
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div id="timer" class="text-4xl md:text-5xl font-black text-white font-mono">00:00</div>
                            </div>
                        </div>
                        <div id="timer-info" class="text-sm text-blue-300 mt-3 uppercase tracking-wider">Aguardando</div>
                    </div>
                </div>

                <!-- Status Indicator -->
                <div class="hud-panel">
                    <div class="text-[#0667DA] text-xs font-bold uppercase tracking-wider mb-4">Status da Conexão</div>
                    <div id="status-indicator" class="flex items-center space-x-3">
                        <div class="relative">
                            <div id="status-pulse" class="w-4 h-4 bg-gray-600 rounded-full"></div>
                            <div id="status-ring" class="absolute inset-0 bg-gray-600 rounded-full animate-ping opacity-0"></div>
                        </div>
                        <div>
                            <div id="status-text" class="text-white font-bold text-sm md:text-base">Offline</div>
                            <div id="status-description" class="text-xs text-gray-400">Aguardando início</div>
                        </div>
                    </div>
                </div>

                <!-- Mission Briefing -->
                <div class="hud-panel hidden md:block">
                    <div class="text-[#0667DA] text-xs font-bold uppercase tracking-wider mb-3">Briefing</div>
                    <div class="text-sm text-gray-300 leading-relaxed space-y-2">
                        <p><span class="text-[#0667DA]">▸</span> Convença o cliente</p>
                        <p><span class="text-[#0667DA]">▸</span> Use argumentos sólidos</p>
                        <p><span class="text-[#0667DA]">▸</span> Supere objeções</p>
                        <p><span class="text-yellow-500">⚠</span> Tempo limitado</p>
                    </div>
                </div>
            </div>

            <!-- Center Panel: Main Display -->
            <div class="lg:col-span-2 space-y-4 md:space-y-6">
                <!-- Voice Visualizer -->
                <div class="hud-panel-large">
                    <div class="text-center py-8 md:py-12">
                        <div id="voice-visualizer" class="mb-6 md:mb-8">
                            <div class="flex items-end justify-center space-x-1 md:space-x-2 h-24 md:h-32">
                                @for($i = 0; $i < 15; $i++)
                                <div class="voice-bar w-1 md:w-2 bg-[#0667DA] rounded-full transition-all duration-150" style="height: 4px;"></div>
                                @endfor
                            </div>
                        </div>
                        <div id="voice-status" class="text-xl md:text-2xl font-bold text-gray-500 mb-3 md:mb-4">Sistema em Standby</div>
                        <div id="voice-subtitle" class="text-xs md:text-sm text-gray-600">Clique em "Iniciar Chamada" para começar</div>
                    </div>
                </div>

                <!-- Transcript Panel -->
                <div class="hud-panel-large">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-[#0667DA] text-xs font-bold uppercase tracking-wider">Transcrição</div>
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-gray-500 uppercase">Live</span>
                        </div>
                    </div>
                    <div id="transcript" class="transcript-container">
                        <p class="text-gray-600 italic text-sm">Aguardando início da transmissão...</p>
                    </div>
                </div>

                <!-- Control Panel -->
                <div class="hud-panel-large">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                        <button id="btn-start" class="combat-btn combat-btn-start w-full sm:w-auto">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                            </svg>
                            <span class="font-black uppercase tracking-wider text-sm md:text-base">Iniciar Chamada</span>
                        </button>
                        <button id="btn-end" class="combat-btn combat-btn-end hidden w-full sm:w-auto">
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08c-.18-.17-.29-.42-.29-.7 0-.28.11-.53.29-.71C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.18.18.29.43.29.71 0 .28-.11.53-.29.71l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.68-1.36-2.66-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z"/>
                            </svg>
                            <span class="font-black uppercase tracking-wider text-sm md:text-base">Encerrar Chamada</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="text-center">
            <!-- Animated Circle -->
            <div class="relative w-32 h-32 mx-auto mb-8">
                <svg class="w-32 h-32 animate-spin" viewBox="0 0 50 50">
                    <circle class="opacity-25" cx="25" cy="25" r="20" stroke="currentColor" stroke-width="4" fill="none" stroke="#0667DA"></circle>
                    <circle class="opacity-75" cx="25" cy="25" r="20" stroke="currentColor" stroke-width="4" fill="none" stroke="#0667DA" stroke-dasharray="80" stroke-dashoffset="60" stroke-linecap="round"></circle>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-12 h-12 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                        <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Loading Text -->
            <div id="loading-text" class="text-2xl font-black text-white mb-2">Conectando...</div>
            <div id="loading-subtitle" class="text-sm text-blue-300">Estabelecendo conexão com a IA</div>
            
            <!-- Animated Dots -->
            <div class="flex justify-center space-x-2 mt-6">
                <div class="w-3 h-3 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0s"></div>
                <div class="w-3 h-3 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                <div class="w-3 h-3 bg-[#0667DA] rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
            </div>
        </div>
    </div>
</div>

<style>
.radar-bg {
    background-image: 
        repeating-conic-gradient(from 0deg at 50% 50%, transparent 0deg, rgba(6, 103, 218, 0.1) 1deg, transparent 2deg),
        radial-gradient(circle at 50% 50%, transparent 30%, rgba(6, 103, 218, 0.05) 30%, transparent 31%, rgba(6, 103, 218, 0.05) 60%, transparent 61%);
    animation: radar-spin 10s linear infinite;
}

@keyframes radar-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.bg-gradient-radial {
    background: radial-gradient(circle at center, var(--tw-gradient-stops));
}

.hud-panel {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.1) 0%, rgba(10, 14, 39, 0.8) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 0.75rem;
    padding: 1rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 20px rgba(6, 103, 218, 0.1);
}

@media (min-width: 768px) {
    .hud-panel {
        padding: 1.5rem;
    }
}

.hud-panel-large {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.05) 0%, rgba(10, 14, 39, 0.9) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 0.75rem;
    padding: 1.25rem;
    backdrop-filter: blur(10px);
    box-shadow: 0 0 30px rgba(6, 103, 218, 0.15);
}

@media (min-width: 768px) {
    .hud-panel-large {
        padding: 2rem;
    }
}

.hud-badge {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.transcript-container {
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(6, 103, 218, 0.2);
    border-radius: 0.5rem;
    padding: 1rem;
    height: 200px;
    overflow-y: auto;
    font-family: 'Courier New', monospace;
    font-size: 0.875rem;
    line-height: 1.6;
}

@media (min-width: 768px) {
    .transcript-container {
        padding: 1.5rem;
        height: 300px;
    }
}

.transcript-container::-webkit-scrollbar {
    width: 6px;
}

.transcript-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.3);
    border-radius: 4px;
}

.transcript-container::-webkit-scrollbar-thumb {
    background: rgba(6, 103, 218, 0.5);
    border-radius: 4px;
}

.transcript-container::-webkit-scrollbar-thumb:hover {
    background: rgba(6, 103, 218, 0.7);
}

.voice-bar {
    animation: voice-idle 2s ease-in-out infinite;
}

.voice-bar:nth-child(odd) {
    animation-delay: 0.1s;
}

.voice-bar:nth-child(even) {
    animation-delay: 0.2s;
}

@keyframes voice-idle {
    0%, 100% { height: 4px; opacity: 0.3; }
    50% { height: 12px; opacity: 0.6; }
}

.combat-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    font-weight: 700;
    transition: all 0.3s ease;
    border: 2px solid;
}

@media (min-width: 768px) {
    .combat-btn {
        gap: 0.75rem;
        padding: 1rem 2rem;
        font-size: 1.125rem;
    }
}

.combat-btn-start {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-color: #34d399;
    color: white;
}

.combat-btn-start:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
}

.combat-btn-end {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-color: #f87171;
    color: white;
}

.combat-btn-end:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(239, 68, 68, 0.5);
}
</style>

@endsection

@section('scripts')
<script>
    // Injetar configuração para o módulo JS
    window.vapiConfig = {
        publicKey: "{{ $vapiPublicKey }}",
        assistantId: "{{ $persona['assistant_id'] }}",
        simulacaoId: {{ $simulacao->id }},
        duracaoMaxima: {{ $duracaoMaxima }}
    };
</script>
@vite('resources/js/vapi-combate.js')
@endsection
