@extends('layout')

@section('title', 'Bem-vindo - Sala de Perigo')

@section('content')
<!-- HOME: Immersive Hero with Holographic Effect -->
<div class="h-screen w-screen relative overflow-hidden bg-[#0a0e27] flex items-center justify-center">
    <!-- Animated Background -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#0667DA]/20 via-transparent to-[#044BA8]/20"></div>
        <div class="hologram-grid"></div>
        <div class="floating-particles"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 flex flex-col items-center justify-center px-4 sm:px-6 max-w-6xl mx-auto">
        <!-- Holographic Logo -->
        <div class="mb-8 sm:mb-12 hologram-float">
            <div class="relative">
                <div class="absolute inset-0 bg-[#0667DA] blur-[60px] sm:blur-[100px] opacity-60 animate-pulse-slow"></div>
                <div class="relative hologram-border p-4 sm:p-8 rounded-2xl sm:rounded-3xl">
                    <svg class="w-20 h-20 sm:w-32 sm:h-32 text-[#0667DA] hologram-glow" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Title with Glitch Effect -->
        <h1 class="text-4xl sm:text-6xl md:text-7xl lg:text-8xl font-black text-white mb-4 sm:mb-6 tracking-tighter glitch-text text-center" data-text="SALA DE PERIGO">
            SALA DE PERIGO
        </h1>
        
        <div class="text-center mb-8 sm:mb-12 max-w-3xl px-4">
            <p class="text-lg sm:text-xl md:text-2xl text-[#0667DA] font-bold mb-2 sm:mb-3 tracking-wide">TREINAMENTO COM IA</p>
            <p class="text-sm sm:text-base md:text-lg text-blue-200/90 leading-relaxed">
                Enfrente clientes simulados por inteligência artificial.<br class="hidden sm:inline"/>
                Aprimore suas habilidades. Domine a arte da persuasão.
            </p>
        </div>

        <!-- CTA Button -->
        <a href="{{ route('selecionar') }}" class="group relative mb-8 sm:mb-12">
            <div class="absolute -inset-1 bg-gradient-to-r from-[#0667DA] to-[#3D8EF7] rounded-full blur-lg opacity-75 group-hover:opacity-100 transition duration-300 animate-pulse-slow"></div>
            <button class="relative px-8 sm:px-12 py-4 sm:py-5 bg-[#0667DA] text-white text-base sm:text-xl font-black rounded-full overflow-hidden transform group-hover:scale-105 transition-all duration-300">
                <span class="relative z-10 flex items-center space-x-2 sm:space-x-3">
                    <span>INICIAR MISSÃO</span>
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                    </svg>
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-700"></div>
            </button>
        </a>

        <!-- Stats Bar -->
        <div class="flex items-center space-x-4 sm:space-x-8 text-center">
            <div class="stat-item">
                <div class="text-2xl sm:text-3xl font-black text-[#0667DA] mb-1">100%</div>
                <div class="text-[10px] sm:text-xs text-blue-300 uppercase tracking-wider">IA Realista</div>
            </div>
            <div class="w-px h-8 sm:h-10 bg-blue-500/30"></div>
            <div class="stat-item">
                <div class="text-2xl sm:text-3xl font-black text-[#0667DA] mb-1">2min</div>
                <div class="text-[10px] sm:text-xs text-blue-300 uppercase tracking-wider">Feedback Rápido</div>
            </div>
            <div class="w-px h-8 sm:h-10 bg-blue-500/30"></div>
            <div class="stat-item">
                <div class="text-2xl sm:text-3xl font-black text-[#0667DA] mb-1">∞</div>
                <div class="text-[10px] sm:text-xs text-blue-300 uppercase tracking-wider">Tentativas</div>
            </div>
        </div>
    </div>
</div>

<style>
.hologram-grid {
    background-image: 
        linear-gradient(rgba(6, 103, 218, 0.15) 2px, transparent 2px),
        linear-gradient(90deg, rgba(6, 103, 218, 0.15) 2px, transparent 2px);
    background-size: 60px 60px;
    animation: grid-flow 20s linear infinite;
}

@keyframes grid-flow {
    0% { background-position: 0 0; }
    100% { background-position: 60px 60px; }
}

.hologram-border {
    border: 2px solid rgba(6, 103, 218, 0.5);
    box-shadow: 
        0 0 20px rgba(6, 103, 218, 0.3),
        inset 0 0 20px rgba(6, 103, 218, 0.1);
}

.hologram-glow {
    filter: drop-shadow(0 0 20px rgba(6, 103, 218, 0.8));
    animation: hologram-pulse 3s ease-in-out infinite;
}

@keyframes hologram-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

.hologram-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.glitch-text {
    position: relative;
    text-shadow: 
        0 0 10px rgba(6, 103, 218, 0.8),
        0 0 20px rgba(6, 103, 218, 0.6),
        0 0 40px rgba(6, 103, 218, 0.4);
}

.glitch-text::before,
.glitch-text::after {
    content: attr(data-text);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.glitch-text::before {
    animation: glitch-1 2.5s infinite;
    color: rgba(6, 103, 218, 0.8);
    z-index: -1;
}

.glitch-text::after {
    animation: glitch-2 2.5s infinite;
    color: rgba(61, 142, 247, 0.8);
    z-index: -2;
}

@keyframes glitch-1 {
    0%, 100% { transform: translate(0); }
    20% { transform: translate(-2px, 2px); }
    40% { transform: translate(-2px, -2px); }
    60% { transform: translate(2px, 2px); }
    80% { transform: translate(2px, -2px); }
}

@keyframes glitch-2 {
    0%, 100% { transform: translate(0); }
    20% { transform: translate(2px, -2px); }
    40% { transform: translate(2px, 2px); }
    60% { transform: translate(-2px, -2px); }
    80% { transform: translate(-2px, 2px); }
}

.animate-pulse-slow {
    animation: pulse-slow 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse-slow {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 0.9; }
}

.stat-item {
    animation: fade-in-up 0.8s ease-out backwards;
}

.stat-item:nth-child(1) { animation-delay: 0.2s; }
.stat-item:nth-child(3) { animation-delay: 0.4s; }
.stat-item:nth-child(5) { animation-delay: 0.6s; }

@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
