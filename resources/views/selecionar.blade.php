@extends('layout')
@section('title', 'Seleção de Adversário')
@section('content')

<div class="h-screen w-screen bg-[#060918] overflow-hidden flex flex-col">

    {{-- BG --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 90% 55% at 50% -5%,rgba(6,103,218,.2),transparent 60%)"></div>
        <div class="absolute inset-0 opacity-[.07]" style="background-image:linear-gradient(rgba(6,103,218,1) 1px,transparent 1px),linear-gradient(90deg,rgba(6,103,218,1) 1px,transparent 1px);background-size:40px 40px"></div>
    </div>

    {{-- HEADER --}}
    <div class="relative z-10 shrink-0 text-center pt-16 pb-3">
        <p class="text-[#0667DA] tracking-[.4em] uppercase font-black text-[10px] mb-1">— Operação Tática —</p>
        <h1 class="text-3xl font-black text-white tracking-tight">SELECIONE SEU <span class="text-[#0667DA]">ADVERSÁRIO</span></h1>
        <div class="h-px w-24 mx-auto mt-2 mb-6 bg-gradient-to-r from-transparent via-[#0667DA] to-transparent"></div>
        
        <div class="flex items-center justify-center gap-3">
            <form action="{{ route('estudio.sync') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-black uppercase tracking-widest hover:bg-emerald-500/20 hover:border-emerald-500/50 hover:text-emerald-400 transition-all" title="Baixar assistentes já criados na Vapi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Sync
                </button>
            </form>
            <a href="{{ route('estudio.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#0667DA]/20 border border-[#0667DA]/50 rounded-xl text-white text-xs font-black uppercase tracking-widest hover:bg-[#0667DA]/40 transition-all">
                ✨ Criar Persona
            </a>
            <button type="button" onclick="iniciarEmboscada()" class="flex items-center gap-2 px-4 py-2 bg-red-600/20 border border-red-500/50 rounded-xl text-red-400 text-xs font-black uppercase tracking-widest hover:bg-red-600/40 hover:text-white transition-all shadow-[0_0_15px_rgba(220,38,38,0.2)]">
                🔥 Emboscada
            </button>
        </div>
    </div>

    <form action="{{ route('iniciar') }}" method="POST" id="form-iniciar" class="relative z-10 flex-1 flex flex-col min-h-0 px-10 pb-8 pt-4 gap-5">
        @csrf

        {{-- NAME --}}
        <div class="shrink-0 flex items-center gap-3 rounded-lg border border-[#0667DA]/30 bg-[#0667DA]/5 px-4 py-2.5">
            <svg class="w-4 h-4 text-[#0667DA] shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
            </svg>
            <span class="text-[#0667DA] text-[10px] font-black uppercase tracking-widest shrink-0">Agente</span>
            <div class="w-px h-4 bg-[#0667DA]/30"></div>
            <input type="text" name="vendedor_nome" required autocomplete="off"
                class="flex-1 bg-transparent border-none outline-none text-white text-sm font-bold placeholder-gray-600"
                placeholder="Digite seu nome de guerra...">
        </div>

        {{-- PERSONA CARDS --}}
        <div class="flex-1 overflow-y-auto min-h-0 px-2 -mx-2">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 p-4 pt-6">
                @foreach($personas as $key => $persona)
                @php
                    $emoji = $persona['emoji'] ?? '🎯';
                    $diffMap = ['Fácil'=>1,'Médio'=>2,'Difícil'=>4,'Muito Difícil'=>5,'Extremo'=>5,'Treinamento'=>3];
                    $bars = $diffMap[$persona['dificuldade']] ?? 4;
                    $tagClass = match($persona['dificuldade']) {
                        'Fácil'        => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/40',
                        'Médio'        => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/40',
                        'Difícil'      => 'bg-orange-500/20 text-orange-400 border-orange-500/40',
                        'Muito Difícil'=> 'bg-red-500/20 text-red-400 border-red-500/40',
                        'Treinamento'  => 'bg-blue-500/20 text-blue-400 border-blue-500/40',
                        default        => 'bg-red-900/30 text-red-300 border-red-700/50',
                    };
                    $barColor = match($persona['dificuldade']) {
                        'Treinamento' => 'bg-blue-500',
                        default => match(true) { $bars>=5=>'bg-red-500', $bars>=4=>'bg-orange-500', $bars>=3=>'bg-yellow-500', default=>'bg-emerald-500' }
                    };
                @endphp
                <label class="cursor-pointer h-full">
                    <input type="radio" name="persona" value="{{ $key }}" data-nome="{{ $persona['nome'] ?? '' }}" class="hidden persona-radio" required>
                    <div class="p-card h-full relative rounded-2xl border-2 border-white/5 overflow-hidden transition-all duration-250 flex flex-col gap-4 p-5">

                        {{-- top glow line --}}
                        <div class="p-line absolute top-0 left-0 right-0 h-[2px] transition-all duration-300 bg-white/5"></div>

                        {{-- header row --}}
                        <div class="flex items-start justify-between">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl bg-[#0667DA]/10 border border-[#0667DA]/20">
                                {{ $emoji }}
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded border {{ $tagClass }}">
                                    {{ $persona['dificuldade'] }}
                                </span>
                                <div class="flex gap-1 items-end">
                                    @for($i=0;$i<5;$i++)
                                    <div class="w-2 rounded-sm {{ $i<$bars ? $barColor : 'bg-white/10' }}"
                                         style="height:{{ 5+$i*4 }}px"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        {{-- name + desc --}}
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <h3 class="text-white font-black text-lg leading-tight">{{ $persona['nome'] }}</h3>
                                <a href="{{ route('estudio.edit', $persona['id'] ?? \App\Models\Persona::where('chave', $key)->value('id')) }}" 
                                   class="text-gray-500 hover:text-emerald-400 bg-white/5 hover:bg-emerald-500/20 p-1.5 rounded-lg transition-colors border border-transparent hover:border-emerald-500/50" 
                                   title="Editar Persona" 
                                   onclick="event.stopPropagation();">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                            <p class="text-gray-500 text-xs leading-relaxed">{{ $persona['descricao'] }}</p>
                        </div>

                        {{-- select button --}}
                        <div class="p-badge mt-auto rounded-xl border-2 py-2.5 text-center transition-all duration-250">
                            <span class="p-badge-label text-[11px] font-black uppercase tracking-wider"></span>
                        </div>

                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- BOTTOM ROW: tempo + submit --}}
        <div class="shrink-0 flex items-stretch gap-4">

            {{-- DURATION CARDS --}}
            <div class="flex-1 flex gap-3">
                @foreach([10=>'10 seg',30=>'30 seg',60=>'1 min',120=>'2 min',0=>'∞'] as $sec=>$lbl)
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="duracao" value="{{ $sec }}" {{ $sec==60?'checked':'' }} class="hidden duracao-radio">
                    <div class="d-card h-full flex flex-col items-center justify-center gap-1 rounded-xl border-2 border-white/5 py-3 px-2 transition-all duration-200">
                        <span class="d-time text-white font-black text-lg leading-none transition-all">{{ $lbl }}</span>
                        <span class="text-gray-600 text-[10px] uppercase tracking-wider font-bold">{{ $sec==0?'livre':($sec<60?'segundos':'minuto') }}</span>
                    </div>
                </label>
                @endforeach
            </div>

            {{-- SUBMIT --}}
            <button type="submit"
                class="shrink-0 group relative overflow-hidden rounded-xl px-10 font-black text-white text-sm uppercase tracking-widest transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(6,103,218,.6)]"
                style="background:linear-gradient(135deg,#0667DA 0%,#3D8EF7 100%)">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/15 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                <span class="relative flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                    </svg>
                    Iniciar Combate
                </span>
            </button>

        </div>

    </form>
</div>

<style>
/* PERSONA CARD */
.p-card {
    background: linear-gradient(160deg, rgba(13,18,53,.95) 0%, rgba(6,9,24,.98) 100%);
    cursor: pointer;
}
.p-card:hover {
    border-color: rgba(6,103,218,.35) !important;
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(6,103,218,.15);
}
.persona-radio:checked + .p-card {
    border-color: #0667DA !important;
    transform: translateY(-4px);
    box-shadow: 0 0 0 1px #0667DA, 0 8px 40px rgba(6,103,218,.35);
}
.persona-radio:checked + .p-card .p-line {
    background: linear-gradient(90deg, transparent, #0667DA, #3D8EF7, transparent);
}
.persona-radio:checked + .p-card .p-badge {
    background: linear-gradient(135deg, #0667DA, #3D8EF7);
    border-color: #3D8EF7;
}
.persona-radio:checked + .p-card .p-badge-label::before { content: "✓  Selecionado"; color: #fff; }
.persona-radio:not(:checked) + .p-card .p-badge {
    background: rgba(255,255,255,.03);
    border-color: rgba(255,255,255,.08);
}
.persona-radio:not(:checked) + .p-card .p-badge-label::before { content: "Selecionar"; color: #6B7280; }

/* DURATION CARD */
.d-card { background: rgba(6,103,218,.04); }
.d-card:hover { border-color: rgba(6,103,218,.25) !important; }
.duracao-radio:checked + .d-card {
    border-color: #0667DA !important;
    background: rgba(6,103,218,.15);
    box-shadow: 0 0 20px rgba(6,103,218,.25);
}
.duracao-radio:checked + .d-card .d-time { color: #3D8EF7; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('desafio') === '1') {
            const inputs = document.querySelectorAll('.persona-radio');
            let srPechinchaInput = null;
            
            inputs.forEach(input => {
                if (input.getAttribute('data-nome').includes('Pechincha')) {
                    srPechinchaInput = input;
                } else {
                    // Oculta visualmente as outras cards
                    input.parentElement.style.opacity = '0.3';
                    input.parentElement.style.pointerEvents = 'none';
                }
            });

            if (srPechinchaInput) {
                // Marca o radio do Pechincha
                srPechinchaInput.checked = true;
                const card = srPechinchaInput.nextElementSibling;
                
                // Realce do desafio
                card.style.borderColor = '#A855F7';
                card.style.boxShadow = '0 0 40px rgba(168,85,247,0.4)';
                card.style.transform = 'scale(1.05)';
                card.style.zIndex = '10';
                
                // Rola para a card
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Foca o campo de nome
                setTimeout(() => {
                    document.querySelector('input[name="vendedor_nome"]').focus();
                }, 800);
            }
        }
    });

    function iniciarEmboscada() {
        const nomeInput = document.querySelector('input[name="vendedor_nome"]');
        if (!nomeInput.value.trim()) {
            alert('Digite seu nome de guerra primeiro!');
            nomeInput.focus();
            return;
        }

        const radios = document.querySelectorAll('.persona-radio');
        if (radios.length === 0) {
            alert('Nenhum adversário disponível!');
            return;
        }

        // Seleciona um index aleatório
        const randomIndex = Math.floor(Math.random() * radios.length);
        const randomRadio = radios[randomIndex];
        
        // Marca o input
        randomRadio.checked = true;
        
        // EFEITO DE SUSPENSE E EMBOSCADA
        // Cria um overlay vermelho pulsante na tela toda
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.backgroundColor = 'rgba(220, 38, 38, 0)';
        overlay.style.zIndex = '9999';
        overlay.style.display = 'flex';
        overlay.style.flexDirection = 'column';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.transition = 'background-color 1s ease-in-out';
        
        const title = document.createElement('h1');
        title.innerHTML = '⚠️ ALERTA DE EMBOSCADA ⚠️<br><span style="font-size:0.5em;color:#fca5a5;">Iniciando interceptação...</span>';
        title.style.color = '#ffffff';
        title.style.fontFamily = 'Inter, sans-serif';
        title.style.fontSize = '4rem';
        title.style.fontWeight = '900';
        title.style.textAlign = 'center';
        title.style.textTransform = 'uppercase';
        title.style.letterSpacing = '5px';
        title.style.opacity = '0';
        title.style.transition = 'opacity 0.5s';
        
        overlay.appendChild(title);
        document.body.appendChild(overlay);
        
        // Animação passo a passo
        setTimeout(() => {
            overlay.style.backgroundColor = 'rgba(153, 27, 27, 0.95)'; // Vermelho Sangue intenso
            title.style.opacity = '1';
        }, 100);

        // Envia o formulário após 2.5 segundos de tensão
        setTimeout(() => {
            document.getElementById('form-iniciar').submit();
        }, 2500);
    }
</script>
@endsection
