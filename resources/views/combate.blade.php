@extends('layout')

@section('title', 'Arena de Combate')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-900 via-alterdata-dark to-black text-white">
    <div class="container mx-auto px-6 py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold mb-2">Arena de Combate</h1>
                <p class="text-blue-200">Enfrentando: <span class="font-bold text-white">{{ $persona['nome'] }}</span></p>
                <p class="text-sm text-gray-400 mt-2">Vendedor: {{ $simulacao->vendedor_nome }}</p>
            </div>

            <!-- Status Card -->
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 mb-8 slide-in">
                <div class="text-center mb-6">
                    <div id="status-indicator" class="w-32 h-32 mx-auto mb-4 relative">
                        <div class="absolute inset-0 bg-gray-600 rounded-full flex items-center justify-center">
                            <svg id="icon-waiting" class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                            <svg id="icon-speaking" class="w-16 h-16 hidden pulse-animation" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z"/>
                                <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z"/>
                            </svg>
                            <svg id="icon-listening" class="w-16 h-16 hidden" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 1c-4.97 0-9 4.03-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2c0-3.87 3.13-7 7-7s7 3.13 7 7v2h-4v8h3c1.66 0 3-1.34 3-3v-7c0-4.97-4.03-9-9-9z"/>
                            </svg>
                        </div>
                    </div>
                    <h2 id="status-text" class="text-2xl font-bold mb-2">Aguardando Início</h2>
                    <p id="status-description" class="text-blue-200">Clique no botão abaixo para começar</p>
                </div>

                <!-- Timer -->
                <div class="text-center mb-6">
                    <div class="inline-block bg-white/20 rounded-full px-8 py-4 transition-all duration-300" id="timer-container">
                        <span class="text-5xl font-mono font-bold transition-all duration-300" id="timer">00:00</span>
                    </div>
                    <div class="text-base font-semibold text-blue-200 mt-3 transition-all duration-300" id="timer-info">Tempo restante</div>
                </div>

                <!-- Controls -->
                <div class="flex justify-center space-x-4">
                    <button id="btn-start" class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-full font-bold text-lg transition transform hover:scale-105 shadow-lg">
                        🎤 Iniciar Chamada
                    </button>
                    <button id="btn-end" class="bg-red-500 hover:bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg transition transform hover:scale-105 shadow-lg hidden">
                        📞 Encerrar Chamada
                    </button>
                </div>
            </div>

            <!-- Transcript -->
            <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-6">
                <h3 class="text-xl font-bold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                    </svg>
                    Transcrição em Tempo Real
                </h3>
                <div id="transcript" class="bg-black/30 rounded-xl p-4 h-64 overflow-y-auto text-sm space-y-2">
                    <p class="text-gray-400 italic">A transcrição aparecerá aqui durante a chamada...</p>
                </div>
            </div>
        </div>
    </div>
</div>
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
