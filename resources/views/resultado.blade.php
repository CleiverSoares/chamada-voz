@extends('layout')

@section('title', 'Resultado da Simulação')

@section('content')
<div class="min-h-screen gradient-bg py-12 px-6">
    <div class="max-w-5xl mx-auto slide-in">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-white mb-4">Resultado da Simulação</h1>
            <p class="text-xl text-blue-100">{{ $simulacao->vendedor_nome }} vs {{ ucfirst(str_replace('_', ' ', $simulacao->persona)) }}</p>
        </div>

        <!-- Score Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-12 mb-8 text-center">
            <h2 class="text-3xl font-bold text-alterdata-dark mb-6">Sua Pontuação</h2>
            
            <div class="relative w-64 h-64 mx-auto mb-8">
                <svg class="transform -rotate-90 w-64 h-64">
                    <circle cx="128" cy="128" r="120" stroke="#E5E7EB" stroke-width="16" fill="none" />
                    <circle cx="128" cy="128" r="120" 
                        stroke="{{ $simulacao->score >= 80 ? '#10B981' : ($simulacao->score >= 60 ? '#F59E0B' : '#EF4444') }}" 
                        stroke-width="16" 
                        fill="none"
                        stroke-dasharray="{{ 2 * 3.14159 * 120 }}"
                        stroke-dashoffset="{{ 2 * 3.14159 * 120 * (1 - $simulacao->score / 100) }}"
                        stroke-linecap="round"
                        class="transition-all duration-1000" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div>
                        <div class="text-7xl font-bold text-alterdata-dark">{{ $simulacao->score }}</div>
                        <div class="text-2xl text-gray-600">/ 100</div>
                    </div>
                </div>
            </div>

            <div class="inline-block px-6 py-3 rounded-full text-xl font-bold
                {{ $simulacao->score >= 80 ? 'bg-green-100 text-green-700' : ($simulacao->score >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                @if($simulacao->score >= 80)
                    🏆 Excelente Desempenho!
                @elseif($simulacao->score >= 60)
                    👍 Bom Trabalho!
                @else
                    💪 Continue Praticando!
                @endif
            </div>
        </div>

        <!-- Feedback Grid -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Pontos Positivos -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Pontos Positivos</h3>
                </div>
                <div class="text-gray-700 leading-relaxed">
                    {{ $simulacao->pontos_positivos ?? 'Análise em processamento...' }}
                </div>
            </div>

            <!-- Pontos de Melhoria -->
            <div class="bg-white rounded-3xl shadow-xl p-8">
                <div class="flex items-center mb-6">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Pontos de Melhoria</h3>
                </div>
                <div class="text-gray-700 leading-relaxed">
                    {{ $simulacao->pontos_melhoria ?? 'Análise em processamento...' }}
                </div>
            </div>
        </div>

        <!-- Detalhes da Chamada -->
        <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6">Detalhes da Chamada</h3>
            <div class="grid md:grid-cols-3 gap-6 text-white">
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ gmdate('i:s', $simulacao->duracao_segundos ?? 0) }}</div>
                    <div class="text-blue-200">Duração</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $simulacao->created_at->format('d/m/Y') }}</div>
                    <div class="text-blue-200">Data</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold mb-2">{{ $simulacao->created_at->timezone('America/Sao_Paulo')->format('H:i') }}</div>
                    <div class="text-blue-200">Horário</div>
                </div>
            </div>
        </div>

        <!-- Transcrição -->
        @if($simulacao->transcricao)
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                <svg class="w-7 h-7 mr-3 text-alterdata-blue" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                </svg>
                Transcrição Completa
            </h3>
            <div class="bg-gray-50 rounded-xl p-6 max-h-96 overflow-y-auto">
                <pre class="whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $simulacao->transcricao }}</pre>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-center space-x-4">
            <a href="{{ route('selecionar') }}" class="bg-white text-alterdata-blue px-10 py-4 rounded-full text-lg font-bold hover:bg-blue-50 transform hover:scale-105 transition shadow-xl">
                🔄 Nova Simulação
            </a>
            <a href="{{ route('home') }}" class="bg-white/20 backdrop-blur text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-white/30 transform hover:scale-105 transition">
                🏠 Voltar ao Início
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Animação do círculo de progresso
    document.addEventListener('DOMContentLoaded', () => {
        const circle = document.querySelector('circle[stroke-dashoffset]');
        if (circle) {
            setTimeout(() => {
                circle.style.transition = 'stroke-dashoffset 2s ease-out';
            }, 100);
        }
    });
</script>
@endsection
