@extends('layout')

@section('title', 'Histórico de Simulações')

@section('body-class', 'overflow-y-auto')
@section('main-class', 'min-h-screen w-screen')

@section('content')
<!-- HISTORY: Data Dashboard -->
<div class="min-h-screen bg-gradient-to-br from-[#0a0e27] via-[#0d1235] to-[#0a0e27] py-8 sm:py-12 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Dashboard Header -->
        <div class="mb-8 sm:mb-12">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-4">
                <div>
                    <div class="text-[#0667DA] text-xs sm:text-sm font-bold uppercase tracking-[0.2em] sm:tracking-[0.3em] mb-2">Sistema de Análise</div>
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight">DASHBOARD DE PERFORMANCE</h1>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 bg-[#0d1235] border border-[#0667DA]/30 rounded-lg sm:rounded-xl px-4 sm:px-6 py-2 sm:py-3">
                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-white font-bold text-sm sm:text-base">Sistema Online</span>
                </div>
            </div>
            <div class="h-1 w-full bg-gradient-to-r from-[#0667DA] via-[#3D8EF7] to-transparent rounded-full"></div>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-8 sm:mb-12">
            <!-- Total Missions -->
            <div class="dashboard-stat-card" style="animation-delay: 0s">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="text-[#0667DA] text-[9px] sm:text-xs font-bold uppercase tracking-wider">Total Missões</div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#0667DA]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-5xl font-black text-white mb-0.5 sm:mb-1">{{ $estatisticas['total'] }}</div>
                <div class="text-xs sm:text-sm text-gray-400">Simulações completas</div>
            </div>
            
            <!-- Average Score -->
            <div class="dashboard-stat-card" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="text-green-400 text-[9px] sm:text-xs font-bold uppercase tracking-wider">Média Geral</div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-5xl font-black text-green-400 mb-0.5 sm:mb-1">{{ $estatisticas['media_score'] ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-gray-400">Pontuação média</div>
            </div>
            
            <!-- Best Score -->
            <div class="dashboard-stat-card" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="text-yellow-400 text-[9px] sm:text-xs font-bold uppercase tracking-wider">Recorde</div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-5xl font-black text-yellow-400 mb-0.5 sm:mb-1">{{ $estatisticas['melhor_score'] ?? 0 }}</div>
                <div class="text-xs sm:text-sm text-gray-400">Melhor pontuação</div>
            </div>
            
            <!-- Total Time -->
            <div class="dashboard-stat-card" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="text-purple-400 text-[9px] sm:text-xs font-bold uppercase tracking-wider">Tempo Total</div>
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-6 sm:h-6 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-3xl sm:text-5xl font-black text-purple-400 mb-0.5 sm:mb-1">{{ gmdate('H:i', $estatisticas['total_tempo'] ?? 0) }}</div>
                <div class="text-xs sm:text-sm text-gray-400">Horas treinadas</div>
            </div>
        </div>
        
        <!-- Dashboard Layout: Ranking & Evolution -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
            
            <!-- Evolution Chart (2/3 width) -->
            <div class="lg:col-span-2 dashboard-table-container p-6 flex flex-col min-h-0">
                <h2 class="text-lg font-black text-white uppercase tracking-tight mb-4 shrink-0">Evolução de Performance</h2>
                @if(isset($grafico_evolucao) && count($grafico_evolucao) > 0)
                <div class="w-full flex-1 min-h-[250px]">
                    <canvas id="evolutionChart"></canvas>
                </div>
                @else
                <div class="flex-1 flex items-center justify-center text-gray-500 text-sm">Sem dados suficientes para o gráfico.</div>
                @endif
            </div>

            <!-- Global Ranking (1/3 width) -->
            <div class="dashboard-table-container flex flex-col min-h-0">
                <div class="p-6 pb-2 border-b border-white/5 shrink-0 flex items-center justify-between">
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">Top Agentes</h2>
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </div>
                <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-2 max-h-[300px]">
                    @if(isset($ranking) && count($ranking) > 0)
                        @foreach($ranking as $index => $rank)
                        <div class="flex items-center gap-3 bg-white/5 border border-white/5 rounded-xl p-3 hover:bg-white/10 transition-colors">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-sm shrink-0
                                {{ $index == 0 ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 shadow-[0_0_10px_rgba(234,179,8,0.3)]' : ($index == 1 ? 'bg-gray-400/20 text-gray-300 border border-gray-400/30 shadow-[0_0_10px_rgba(156,163,175,0.2)]' : ($index == 2 ? 'bg-orange-700/20 text-orange-400 border border-orange-700/30 shadow-[0_0_10px_rgba(194,65,12,0.2)]' : 'bg-white/5 text-gray-500')) }}">
                                {{ $index + 1 }}º
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-white font-bold text-sm truncate flex items-center gap-2">
                                    {{ $rank->vendedor_nome }}
                                    @if($index == 0)<span class="text-[10px] bg-yellow-500 text-black px-1.5 py-0.5 rounded-sm font-black uppercase tracking-widest">MVP</span>@endif
                                </div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider flex items-center gap-1.5 mt-0.5">
                                    <span class="{{ $rank->patente == 'Lenda da Arena' ? 'text-purple-400' : ($rank->patente == 'Forças Especiais' ? 'text-red-400' : ($rank->patente == 'Combatente Tático' ? 'text-blue-400' : 'text-gray-400')) }}">⚔️ {{ $rank->patente }}</span>
                                    <span class="text-white/20">•</span>
                                    <span>{{ $rank->total_missoes }} Missões</span>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-emerald-400 font-black text-lg leading-none">{{ number_format($rank->total_xp, 0, ',', '.') }}</div>
                                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Total XP</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 text-sm py-8">Nenhum agente no ranking.</div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Dashboard Layout: Second Row (Badges & Objections) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
            
            <!-- Badges (2/3 width) -->
            <div class="lg:col-span-2 dashboard-table-container p-6 flex flex-col min-h-0">
                <div class="pb-2 border-b border-white/5 shrink-0 flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">Suas Conquistas</h2>
                </div>
                <div class="flex-1 grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="flex flex-col items-center justify-center text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:border-yellow-500/50 transition-colors">
                        <span class="text-4xl mb-2 filter drop-shadow-[0_0_15px_rgba(234,179,8,.5)]">🎖️</span>
                        <span class="text-white font-bold text-sm">Quebra-Gelo</span>
                        <span class="text-gray-500 text-[10px] uppercase mt-1">Converteu em < 30s</span>
                    </div>
                    <div class="flex flex-col items-center justify-center text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:border-blue-500/50 transition-colors">
                        <span class="text-4xl mb-2 filter drop-shadow-[0_0_15px_rgba(59,130,246,.5)]">🛡️</span>
                        <span class="text-white font-bold text-sm">Mestre da Objeção</span>
                        <span class="text-gray-500 text-[10px] uppercase mt-1">100% contra Extremo</span>
                    </div>
                    <div class="flex flex-col items-center justify-center text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:border-emerald-500/50 transition-colors opacity-50 grayscale">
                        <span class="text-4xl mb-2 filter drop-shadow-[0_0_15px_rgba(16,185,129,.5)]">🥇</span>
                        <span class="text-white font-bold text-sm">O Intocável</span>
                        <span class="text-gray-500 text-[10px] uppercase mt-1">5 Vitórias Seguidas</span>
                    </div>
                    <div class="flex flex-col items-center justify-center text-center p-4 bg-white/5 rounded-xl border border-white/10 hover:border-purple-500/50 transition-colors opacity-50 grayscale">
                        <span class="text-4xl mb-2 filter drop-shadow-[0_0_15px_rgba(168,85,247,.5)]">🎯</span>
                        <span class="text-white font-bold text-sm">Fechador</span>
                        <span class="text-gray-500 text-[10px] uppercase mt-1">Score Max no Fechamento</span>
                    </div>
                </div>
            </div>

            <!-- Objections Word Cloud (1/3 width) -->
            <div class="dashboard-table-container p-6 flex flex-col min-h-0">
                <div class="pb-2 border-b border-white/5 shrink-0 flex items-center justify-between mb-4">
                    <h2 class="text-lg font-black text-white uppercase tracking-tight">Radar de Objeções</h2>
                </div>
                <div class="flex-1 flex flex-wrap content-start gap-2 pt-2">
                    <span class="px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-full text-base font-black">"Tá muito caro"</span>
                    <span class="px-3 py-1 bg-orange-500/20 text-orange-400 border border-orange-500/30 rounded-full text-xs font-bold">"Sem tempo"</span>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 rounded-full text-lg font-black">"Já uso outro"</span>
                    <span class="px-3 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-full text-[10px] font-bold">"Manda por email"</span>
                    <span class="px-3 py-1 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded-full text-sm font-bold">"Vou pensar"</span>
                    <span class="px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-bold">"Falo com o sócio"</span>
                </div>
            </div>

        </div>

        <!-- Desafio da Semana -->
        <div class="dashboard-table-container p-8 mb-8 sm:mb-12 border-purple-500/30 relative overflow-hidden flex flex-col sm:flex-row items-center sm:items-start gap-6 justify-between" style="background: linear-gradient(135deg, rgba(88,28,135,0.4) 0%, rgba(10,14,39,0.9) 100%);">
            <div class="absolute -right-10 -top-10 text-9xl opacity-10 select-none">🏆</div>
            <div class="relative z-10 text-center sm:text-left">
                <h2 class="text-xs font-black text-purple-400 uppercase tracking-widest mb-1">🔥 Desafio da Semana</h2>
                <h3 class="text-2xl font-black text-white mb-2">A Venda Sem Desconto</h3>
                <p class="text-gray-400 text-sm max-w-2xl mb-0">O cliente "Sr. Pechincha" exige 30% de desconto. Sua missão é fechar a venda usando apenas valor agregado, sem conceder nenhum centavo! Você tem 2 minutos.</p>
            </div>
            <a href="{{ route('selecionar') }}?desafio=1" class="relative z-10 shrink-0 flex items-center gap-2 px-8 py-3 bg-purple-600 hover:bg-purple-500 text-white font-black text-sm uppercase tracking-widest rounded-xl transition-all hover:scale-105 shadow-[0_0_20px_rgba(147,51,234,.4)]">
                Aceitar Desafio
            </a>
        </div>
        
        @if(isset($grafico_evolucao) && count($grafico_evolucao) > 0)
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('evolutionChart').getContext('2d');
                
                // Gradiente para a linha
                let gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(6, 103, 218, 0.5)');
                gradient.addColorStop(1, 'rgba(6, 103, 218, 0.0)');

                const rawData = @json($grafico_evolucao);
                const labels = rawData.map(d => d.data);
                const data = rawData.map(d => d.score);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Score Geral',
                            data: data,
                            borderColor: '#3D8EF7',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#0667DA',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                                ticks: { color: 'rgba(255, 255, 255, 0.5)' }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: { color: 'rgba(255, 255, 255, 0.5)', maxTicksLimit: 10 }
                            }
                        }
                    }
                });
            });
        </script>
        @endif

        @if($simulacoes->count() > 0)
        <!-- Mission Log Table -->
        <div class="dashboard-table-container">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 px-4 sm:px-6 pt-4 sm:pt-6 gap-2">
                <h2 class="text-xl sm:text-2xl font-black text-white uppercase tracking-tight">Registro de Missões</h2>
                <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-400">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                    <span>{{ $simulacoes->total() }} registros</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-y border-[#0667DA]/30">
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider">Agente</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider hidden sm:table-cell">Alvo</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider">Score</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider hidden md:table-cell">Duração</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider hidden lg:table-cell">Data/Hora</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-[9px] sm:text-xs font-bold text-[#0667DA] uppercase tracking-wider">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#0667DA]/10">
                        @foreach($simulacoes as $sim)
                        <tr class="mission-row hover:bg-[#0667DA]/5 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-[#0667DA]/20 rounded-lg flex items-center justify-center">
                                        <span class="text-lg">👤</span>
                                    </div>
                                    <div class="font-bold text-white">{{ $sim->vendedor_nome }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <div class="inline-flex items-center space-x-2 px-3 py-1 bg-[#0d1235] border border-[#0667DA]/30 rounded-lg">
                                    <span class="text-lg">{{ \App\Http\Controllers\SimulacaoController::emojiPersona($sim->persona) }}</span>
                                    <span class="text-sm font-semibold text-white">{{ \App\Http\Controllers\SimulacaoController::nomePersona($sim->persona) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <div class="relative w-16 h-16">
                                        <svg class="transform -rotate-90 w-16 h-16">
                                            <circle cx="32" cy="32" r="28" stroke="rgba(6, 103, 218, 0.2)" stroke-width="4" fill="none"/>
                                            <circle cx="32" cy="32" r="28" 
                                                stroke="{{ $sim->score >= 80 ? '#10B981' : ($sim->score >= 60 ? '#F59E0B' : '#F97316') }}" 
                                                stroke-width="4" 
                                                fill="none"
                                                stroke-dasharray="{{ 2 * 3.14159 * 28 }}"
                                                stroke-dashoffset="{{ 2 * 3.14159 * 28 * (1 - $sim->score / 100) }}"
                                                stroke-linecap="round"/>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-lg font-black text-white">{{ $sim->score }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center space-x-2 text-gray-300">
                                    <svg class="w-4 h-4 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                    </svg>
                                    <span class="font-mono font-semibold">{{ gmdate('i:s', $sim->duracao_segundos ?? 0) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-white font-semibold">{{ $sim->created_at->timezone('America/Sao_Paulo')->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500 font-mono">{{ $sim->created_at->timezone('America/Sao_Paulo')->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('resultado', $sim->id) }}" 
                                   class="inline-flex items-center space-x-2 px-4 py-2 bg-[#0667DA] hover:bg-[#3D8EF7] text-white rounded-lg transition-all transform hover:scale-105 font-bold text-sm">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    <span>ANALISAR</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($simulacoes->hasPages())
            <div class="px-6 py-6 border-t border-[#0667DA]/30">
                {{ $simulacoes->links() }}
            </div>
            @endif
        </div>
        @else
        <!-- Empty State -->
        <div class="dashboard-empty-state">
            <div class="text-center py-20">
                <div class="mb-8">
                    <div class="inline-block p-6 bg-[#0667DA]/10 rounded-full border-2 border-[#0667DA]/30">
                        <svg class="w-24 h-24 text-[#0667DA]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mb-3 uppercase">Nenhuma Missão Registrada</h3>
                <p class="text-gray-400 mb-8 text-lg">Inicie sua primeira simulação para começar a coletar dados</p>
                <a href="{{ route('selecionar') }}" class="inline-flex items-center space-x-3 px-8 py-4 bg-[#0667DA] hover:bg-[#3D8EF7] text-white rounded-xl font-black uppercase tracking-wider transition-all transform hover:scale-105">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                    <span>Iniciar Primeira Missão</span>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.dashboard-stat-card {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.1) 0%, rgba(10, 14, 39, 0.8) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 1rem;
    padding: 1.5rem;
    animation: fade-in-up 0.6s ease-out backwards;
}

@keyframes fade-in-up {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-table-container {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.05) 0%, rgba(10, 14, 39, 0.9) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 1rem;
    overflow: hidden;
    animation: fade-in 0.8s ease-out 0.4s backwards;
}

@keyframes fade-in {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

.dashboard-empty-state {
    background: linear-gradient(135deg, rgba(6, 103, 218, 0.05) 0%, rgba(10, 14, 39, 0.9) 100%);
    border: 1px solid rgba(6, 103, 218, 0.3);
    border-radius: 1rem;
    animation: fade-in 0.8s ease-out 0.4s backwards;
}

.mission-row {
    animation: fade-in 0.4s ease-out backwards;
}

.mission-row:nth-child(1) { animation-delay: 0.1s; }
.mission-row:nth-child(2) { animation-delay: 0.15s; }
.mission-row:nth-child(3) { animation-delay: 0.2s; }
.mission-row:nth-child(4) { animation-delay: 0.25s; }
.mission-row:nth-child(5) { animation-delay: 0.3s; }
</style>
@endsection
