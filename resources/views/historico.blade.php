@extends('layout')

@section('title', 'Histórico de Simulações')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-alterdata-dark mb-2">📊 Dashboard de Resultados</h1>
            <p class="text-gray-600">Acompanhe o desempenho de todos os treinamentos</p>
        </div>
        
        <!-- Estatísticas -->
        <div class="grid md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-alterdata-blue">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-600">Total de Simulações</div>
                    <svg class="w-8 h-8 text-alterdata-blue" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-alterdata-dark">{{ $estatisticas['total'] }}</div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-600">Média de Pontuação</div>
                    <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-green-600">{{ $estatisticas['media_score'] ?? 0 }}</div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-600">Melhor Pontuação</div>
                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-yellow-600">{{ $estatisticas['melhor_score'] ?? 0 }}</div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-sm font-semibold text-gray-600">Tempo Total</div>
                    <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-purple-600">{{ gmdate('H:i', $estatisticas['total_tempo'] ?? 0) }}</div>
            </div>
        </div>

        @if($simulacoes->count() > 0)
        <!-- Tabela -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-alterdata-blue to-alterdata-dark text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Vendedor</th>
                            <th class="px-6 py-4 text-left font-semibold">Persona</th>
                            <th class="px-6 py-4 text-center font-semibold">Score</th>
                            <th class="px-6 py-4 text-center font-semibold">Duração</th>
                            <th class="px-6 py-4 text-center font-semibold">Data</th>
                            <th class="px-6 py-4 text-center font-semibold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($simulacoes as $sim)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $sim->vendedor_nome }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $sim->persona === 'seu_mario' ? '🏪 Seu Mário' : '👩‍💼 Dona Sônia' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full font-bold text-xl
                                    {{ $sim->score >= 80 ? 'bg-green-100 text-green-700' : ($sim->score >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $sim->score }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                {{ gmdate('i:s', $sim->duracao_segundos ?? 0) }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">
                                <div>{{ $sim->created_at->timezone('America/Sao_Paulo')->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $sim->created_at->timezone('America/Sao_Paulo')->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('resultado', $sim->id) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-alterdata-blue text-white rounded-lg hover:bg-alterdata-dark transition font-semibold">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="mt-6">
            {{ $simulacoes->links() }}
        </div>
        @else
        <!-- Estado vazio -->
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Nenhuma simulação concluída ainda</h3>
            <p class="text-gray-500 mb-6">Comece seu primeiro treinamento para ver os resultados aqui</p>
            <a href="{{ route('selecionar') }}" class="inline-block bg-alterdata-blue text-white px-8 py-3 rounded-full font-bold hover:bg-alterdata-dark transition">
                Iniciar Primeira Simulação
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
