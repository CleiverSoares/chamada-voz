@extends('layout')

@section('title', 'Selecionar Persona')

@section('content')
<!-- SELECTION: Character Selection Screen (Game-like) -->
<div class="min-h-screen w-screen bg-gradient-to-b from-[#0a0e27] via-[#0d1235] to-[#0a0e27] flex items-center justify-center px-4 sm:px-6 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto w-full">
        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="text-[#0667DA] text-[10px] sm:text-xs font-bold tracking-[0.2em] sm:tracking-[0.3em] uppercase mb-1">Missão 01</div>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white mb-2 tracking-tight">SELECIONE SEU OPONENTE</h1>
            <div class="h-1 w-16 sm:w-24 bg-gradient-to-r from-transparent via-[#0667DA] to-transparent mx-auto"></div>
        </div>

        <form action="{{ route('iniciar') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Player Name Input -->
            <div class="max-w-2xl mx-auto">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-[#0667DA] to-[#3D8EF7] rounded-lg opacity-30 group-hover:opacity-50 blur transition"></div>
                    <div class="relative bg-[#0d1235] border border-[#0667DA]/30 rounded-lg p-3 sm:p-4">
                        <label class="block text-[#0667DA] font-bold mb-2 text-[10px] sm:text-xs uppercase tracking-wider">Identificação do Agente</label>
                        <input type="text" name="vendedor_nome" required 
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 bg-black/50 border-2 border-[#0667DA]/50 rounded-lg focus:border-[#0667DA] focus:outline-none text-white text-base sm:text-lg font-semibold placeholder-gray-600 transition"
                            placeholder="Digite seu nome de guerra">
                    </div>
                </div>
            </div>

            <!-- Character Selection -->
            <div class="grid sm:grid-cols-2 gap-4 sm:gap-6 max-w-5xl mx-auto">
                @foreach($personas as $key => $persona)
                <label class="cursor-pointer">
                    <input type="radio" name="persona" value="{{ $key }}" class="hidden persona-radio" required>
                    <div class="persona-card relative overflow-hidden rounded-xl sm:rounded-2xl transition-all duration-300 border-4 border-gray-700/50 hover:border-gray-600">
                        <div class="relative p-4 sm:p-6 bg-gradient-to-br from-[#0d1235] to-[#0a0e27]">
                            <!-- Character Avatar -->
                            <div class="mb-3 sm:mb-4 flex justify-center">
                                <div class="relative">
                                    <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-[#0667DA]/20 to-[#044BA8]/20 flex items-center justify-center border-4 border-[#0667DA]/30">
                                        <span class="text-4xl sm:text-6xl">{{ $key === 'seu_mario' ? '🏪' : '👩‍💼' }}</span>
                                    </div>
                                    <div class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-red-600 text-white px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[10px] sm:text-xs font-black uppercase border-2 border-red-800">
                                        {{ $persona['dificuldade'] }}
                                    </div>
                                </div>
                            </div>

                            <!-- Character Info -->
                            <div class="text-center mb-3 sm:mb-4">
                                <h3 class="text-2xl sm:text-3xl font-black text-white mb-1 sm:mb-2">{{ $persona['nome'] }}</h3>
                                <p class="text-blue-300 text-xs sm:text-sm leading-relaxed">{{ $persona['descricao'] }}</p>
                            </div>

                            <!-- Stats -->
                            <div class="space-y-1.5 sm:space-y-2 mb-2 sm:mb-3">
                                <div class="flex items-center justify-between text-[10px] sm:text-xs">
                                    <span class="text-gray-400 uppercase tracking-wider font-semibold">Dificuldade</span>
                                    <span class="text-[#0667DA] font-bold">{{ $key === 'seu_mario' ? '★★★★☆' : '★★★★★' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[10px] sm:text-xs">
                                    <span class="text-gray-400 uppercase tracking-wider font-semibold">Resistência</span>
                                    <span class="text-[#0667DA] font-bold">{{ $key === 'seu_mario' ? 'ALTA' : 'EXTREMA' }}</span>
                                </div>
                            </div>

                            <!-- Selection Indicator (always visible) -->
                            <div class="selection-badge text-center py-1.5 sm:py-2 rounded-lg bg-gray-800/50 border-2 border-gray-700">
                                <span class="text-gray-500 text-xs sm:text-sm font-bold uppercase">Clique para selecionar</span>
                            </div>
                        </div>
                    </div>
                </label>
                @endforeach
            </div>
            @error('persona')
                <p class="text-red-500 text-center text-sm">{{ $message }}</p>
            @enderror

            <!-- Duration Selection -->
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-3 sm:mb-4">
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-1">DURAÇÃO DA MISSÃO</h3>
                    <p class="text-blue-300 text-xs sm:text-sm">Escolha quanto tempo você terá</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-4">
                    @foreach([10 => '10s', 30 => '30s', 60 => '1min', 120 => '2min'] as $seconds => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="duracao" value="{{ $seconds }}" {{ $seconds == 60 ? 'checked' : '' }} class="hidden duracao-radio">
                        <div class="duracao-card relative rounded-lg sm:rounded-xl p-3 sm:p-4 bg-gradient-to-br from-[#0d1235] to-[#0a0e27] border-4 border-gray-700/50 hover:border-gray-600 transition-all duration-300">
                            <div class="text-center">
                                <div class="text-3xl sm:text-4xl font-black text-white mb-0.5 sm:mb-1">{{ strpos($label, 's') !== false ? rtrim($label, 's') : rtrim($label, 'min') }}</div>
                                <div class="text-[10px] sm:text-xs text-blue-300 uppercase tracking-wider font-semibold mb-1.5 sm:mb-2">{{ strpos($label, 'min') !== false ? 'min' : 'seg' }}</div>
                                <div class="duration-badge py-0.5 sm:py-1 px-2 sm:px-3 rounded-full bg-gray-800/50 border-2 border-gray-700">
                                    <span class="text-gray-500 text-[10px] sm:text-xs font-bold uppercase">-</span>
                                </div>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                <!-- Custom Duration -->
                {{-- <div class="max-w-md mx-auto">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-[#0667DA] to-[#3D8EF7] rounded-lg opacity-20 group-hover:opacity-30 blur transition"></div>
                        <div class="relative bg-[#0d1235] border border-[#0667DA]/30 rounded-lg p-4"> 
                            <label class="block text-[#0667DA] font-bold mb-2 text-xs uppercase tracking-wider text-center">⚙️ Tempo Personalizado</label>
                            <div class="flex items-center space-x-3">
                                <input type="number" id="duracao-custom" min="10" max="600" step="5" placeholder="Ex: 45"
                                    class="flex-1 px-4 py-3 bg-black/50 border-2 border-[#0667DA]/50 rounded-lg focus:border-[#0667DA] focus:outline-none text-white text-center text-lg font-bold placeholder-gray-600 transition">
                                <button type="button" id="btn-apply-custom" class="px-6 py-3 bg-[#0667DA] hover:bg-[#3D8EF7] text-white rounded-lg font-bold text-sm uppercase tracking-wider transition-all transform hover:scale-105">
                                    Aplicar
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-2">Mínimo: 10s | Máximo: 600s (10min)</p>
                        </div>
                    </div>
                </div> --}}
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="group relative inline-block">
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#0667DA] via-[#3D8EF7] to-[#0667DA] rounded-full blur-xl opacity-75 group-hover:opacity-100 transition duration-300"></div>
                    <div class="relative px-8 sm:px-12 py-3 sm:py-4 bg-[#0667DA] text-white text-base sm:text-xl font-black rounded-full overflow-hidden transform group-hover:scale-105 transition-all duration-300 border-2 border-[#3D8EF7]">
                        <span class="relative z-10 flex items-center space-x-2 sm:space-x-3">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                            </svg>
                            <span>ENTRAR EM COMBATE</span>
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 group-hover:translate-x-2 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent translate-x-[-200%] group-hover:translate-x-[200%] transition-transform duration-700"></div>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Persona selection styles */
.persona-radio:checked + .persona-card {
    border-color: #0667DA !important;
    box-shadow: 0 0 40px rgba(6, 103, 218, 0.6), 0 0 20px rgba(6, 103, 218, 0.4) inset;
    transform: scale(1.03);
}

.persona-radio:checked + .persona-card .selection-badge {
    background: linear-gradient(135deg, #0667DA, #3D8EF7) !important;
    border-color: #0667DA !important;
}

.persona-radio:checked + .persona-card .selection-badge span {
    color: white !important;
}

.persona-radio:checked + .persona-card .selection-badge span::before {
    content: "✓ SELECIONADO";
}

.persona-radio:not(:checked) + .persona-card .selection-badge span::before {
    content: "Clique para selecionar";
}

/* Duration selection styles */
.duracao-radio:checked + .duracao-card {
    border-color: #0667DA !important;
    box-shadow: 0 0 30px rgba(6, 103, 218, 0.6);
    transform: scale(1.05);
}

.duracao-radio:checked + .duracao-card .duration-badge {
    background: linear-gradient(135deg, #0667DA, #3D8EF7) !important;
    border-color: #0667DA !important;
}

.duracao-radio:checked + .duracao-card .duration-badge span {
    color: white !important;
}

.duracao-radio:checked + .duracao-card .duration-badge span::before {
    content: "✓ ATIVO";
}

.duracao-radio:not(:checked) + .duracao-card .duration-badge span::before {
    content: "-";
}
</style>

<script>
// Garantir que os indicadores visuais funcionem
document.addEventListener('DOMContentLoaded', function() {
    // Forçar atualização visual ao carregar (para o 2 minutos que vem checked)
    const checkedDuracao = document.querySelector('.duracao-radio:checked');
    if (checkedDuracao) {
        checkedDuracao.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection


@section('scripts')
<script>
// Custom duration handler
document.addEventListener('DOMContentLoaded', function() {
    const customInput = document.getElementById('duracao-custom');
    const btnApply = document.getElementById('btn-apply-custom');
    const duracaoRadios = document.querySelectorAll('.duracao-radio');
    
    if (btnApply && customInput) {
        btnApply.addEventListener('click', function() {
            const customValue = parseInt(customInput.value);
            
            if (!customValue || customValue < 10) {
                alert('Por favor, insira um valor mínimo de 10 segundos');
                return;
            }
            
            if (customValue > 600) {
                alert('O tempo máximo é 600 segundos (10 minutos)');
                return;
            }
            
            // Desmarcar todos os radios
            duracaoRadios.forEach(radio => radio.checked = false);
            
            // Criar um input hidden com o valor personalizado
            let hiddenInput = document.querySelector('input[name="duracao"][type="hidden"]');
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'duracao';
                customInput.parentElement.appendChild(hiddenInput);
            }
            hiddenInput.value = customValue;
            
            // Feedback visual
            btnApply.textContent = '✓ Aplicado';
            btnApply.classList.add('bg-green-500');
            btnApply.classList.remove('bg-[#0667DA]');
            
            setTimeout(() => {
                btnApply.textContent = 'Aplicar';
                btnApply.classList.remove('bg-green-500');
                btnApply.classList.add('bg-[#0667DA]');
            }, 2000);
        });
    }
});
</script>
@endsection
