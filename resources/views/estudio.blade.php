@extends('layout')
@section('title', isset($persona) ? 'Editar Persona' : 'Estúdio de Personas')
@section('content')

<div class="h-screen w-screen bg-[#060918] overflow-hidden flex flex-col">

    {{-- BG --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute inset-0 opacity-[.06]" style="background-image:linear-gradient(rgba(6,103,218,1) 1px,transparent 1px),linear-gradient(90deg,rgba(6,103,218,1) 1px,transparent 1px);background-size:40px 40px"></div>
        <div class="absolute inset-0" style="background:radial-gradient(ellipse 70% 45% at 50% 0%,rgba(6,103,218,.18),transparent 65%)"></div>
    </div>

    {{-- HEADER --}}
    <div class="relative z-10 shrink-0 text-center pt-10 pb-3">
        <p class="text-[#0667DA] tracking-[.4em] uppercase font-black text-[10px] mb-1">— Inteligência Artificial —</p>
        <h1 class="text-3xl font-black text-white tracking-tight">{{ isset($persona) ? 'EDITAR' : 'ESTÚDIO DE' }} <span class="text-[#0667DA]">PERSONAS</span></h1>
        <div class="h-px w-24 mx-auto mt-2 bg-gradient-to-r from-transparent via-[#0667DA] to-transparent"></div>
    </div>

    {{-- FORM --}}
    <div class="relative z-10 flex-1 overflow-y-auto px-8 pb-12 pt-4">
        <div class="max-w-4xl mx-auto">
            
            @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 font-bold text-sm">
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ isset($persona) ? route('estudio.update', $persona->id) : route('estudio.store') }}" method="POST" class="bg-[#0667DA]/5 border border-[#0667DA]/20 rounded-2xl p-8 backdrop-blur-sm space-y-6">
                @csrf
                @if(isset($persona))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Nome do Cliente</label>
                        <input type="text" name="nome" required value="{{ old('nome', $persona->nome ?? '') }}"
                            class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                            placeholder="Ex: Carlos - O Cético">
                    </div>

                    <div>
                        <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Emoji</label>
                        <input type="text" name="emoji" required value="{{ old('emoji', $persona->emoji ?? '👤') }}"
                            class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                            placeholder="Ex: 😠">
                    </div>
                </div>

                <div>
                    <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Breve Descrição (Para o Vendedor ver)</label>
                    <input type="text" name="descricao" required value="{{ old('descricao', $persona->descricao ?? '') }}"
                        class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                        placeholder="Ex: Cliente difícil, dono de padaria, focado em custos...">
                </div>

                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Dificuldade</label>
                        <select name="dificuldade" required
                            class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all">
                            @foreach(['Fácil', 'Médio', 'Difícil', 'Muito Difícil', 'Extremo', 'Treinamento'] as $diff)
                                <option value="{{ $diff }}" {{ old('dificuldade', $persona->dificuldade ?? '') == $diff ? 'selected' : '' }}>{{ $diff }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Provedor de Voz</label>
                        <select name="voce_provider" required
                            class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all">
                            <option value="vapi" {{ old('voce_provider', $persona->voce_provider ?? 'vapi') == 'vapi' ? 'selected' : '' }}>Vapi Native</option>
                            <option value="openai" {{ old('voce_provider', $persona->voce_provider ?? '') == 'openai' ? 'selected' : '' }}>OpenAI</option>
                            <option value="11labs" {{ old('voce_provider', $persona->voce_provider ?? '') == '11labs' ? 'selected' : '' }}>ElevenLabs</option>
                            <option value="playht" {{ old('voce_provider', $persona->voce_provider ?? '') == 'playht' ? 'selected' : '' }}>PlayHT</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">ID da Voz</label>
                        <input type="text" name="voce_id" required value="{{ old('voce_id', $persona->voce_id ?? 'alloy') }}"
                            class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                            placeholder="Ex: alloy, echo, nova...">
                        <p class="text-[9px] text-gray-500 mt-1">OpenAI: alloy, echo, fable, onyx, nova, shimmer</p>
                    </div>
                </div>

                <div>
                    <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Primeira Mensagem da IA</label>
                    <input type="text" name="primeira_mensagem" required value="{{ old('primeira_mensagem', $persona->primeira_mensagem ?? '') }}"
                        class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                        placeholder="Ex: Alô? Fala rápido que estou ocupado.">
                </div>

                <div>
                    <label class="block text-[#0667DA] text-xs font-black uppercase tracking-widest mb-2">Contexto / Prompt da IA (A Alma do Cliente)</label>
                    <textarea name="prompt" required rows="6"
                        class="w-full bg-[#060918]/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#0667DA] focus:ring-1 focus:ring-[#0667DA] outline-none transition-all"
                        placeholder="Ex: Você é um cliente muito exigente... Você deve fazer x objeções... Não feche a venda a menos que..."
                    >{{ old('prompt', $persona->prompt ?? '') }}</textarea>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('selecionar') }}" class="text-gray-400 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">← Voltar</a>
                    
                    <button type="submit"
                        class="group relative overflow-hidden rounded-xl px-10 py-3 font-black text-white text-sm uppercase tracking-widest transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_30px_rgba(6,103,218,.5)]"
                        style="background:linear-gradient(135deg,#0667DA 0%,#3D8EF7 100%)">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/15 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-500"></div>
                        <span class="relative">{{ isset($persona) ? 'Salvar Alterações na Vapi' : 'Gerar Persona na Vapi' }}</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
