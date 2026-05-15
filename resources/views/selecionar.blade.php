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
        <div class="h-px w-24 mx-auto mt-2 bg-gradient-to-r from-transparent via-[#0667DA] to-transparent"></div>
    </div>

    <form action="{{ route('iniciar') }}" method="POST" class="relative z-10 flex-1 flex flex-col min-h-0 px-10 pb-8 pt-4 gap-5">
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

        {{-- PERSONA CARDS — 3 colunas --}}
        <div class="grid grid-cols-3 gap-5 shrink-0">
            @foreach($personas as $key => $persona)
            @php
                $emoji = match($key) { 'seu_mario'=>'🏪', 'dona_sonia'=>'👩‍💼', 'flavio_academia_vendas'=>'🏆', default=>'🎯' };
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
            <label class="cursor-pointer">
                <input type="radio" name="persona" value="{{ $key }}" class="hidden persona-radio" required>
                <div class="p-card relative rounded-2xl border-2 border-white/5 overflow-hidden transition-all duration-250 flex flex-col gap-4 p-5">

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
                    <div>
                        <h3 class="text-white font-black text-lg leading-tight mb-1">{{ $persona['nome'] }}</h3>
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

@endsection
