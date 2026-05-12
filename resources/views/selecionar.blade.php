@extends('layout')

@section('title', 'Selecionar Persona')

@section('content')
<div class="container mx-auto px-6 py-12">
    <div class="max-w-4xl mx-auto slide-in">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-alterdata-dark mb-4">Escolha Seu Desafio</h1>
            <p class="text-gray-600 text-lg">Selecione a persona que você deseja enfrentar</p>
        </div>

        <form action="{{ route('iniciar') }}" method="POST">
            @csrf
            
            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-3 text-lg">Seu Nome</label>
                <input type="text" name="vendedor_nome" required 
                    class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl focus:border-alterdata-blue focus:outline-none text-lg"
                    placeholder="Digite seu nome completo">
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-4 text-lg">Selecione a Persona</label>
                <div class="grid md:grid-cols-2 gap-6">
                    @foreach($personas as $key => $persona)
                    <label class="cursor-pointer">
                        <input type="radio" name="persona" value="{{ $key }}" class="hidden peer">
                        <div class="border-3 border-gray-300 rounded-2xl p-6 hover:border-alterdata-blue transition peer-checked:border-alterdata-blue peer-checked:bg-blue-50 peer-checked:shadow-xl">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-alterdata-dark mb-2">{{ $persona['nome'] }}</h3>
                                    <span class="inline-block px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                        {{ $persona['dificuldade'] }}
                                    </span>
                                </div>
                                <svg class="w-8 h-8 text-alterdata-blue hidden peer-checked:block" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                            </div>
                            <p class="text-gray-600">{{ $persona['descricao'] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('persona')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-semibold mb-3 text-lg">Duração da Simulação</label>
                <div class="grid grid-cols-4 gap-4">
                    <label class="cursor-pointer">
                        <input type="radio" name="duracao" value="60" class="hidden peer">
                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-alterdata-blue transition peer-checked:border-alterdata-blue peer-checked:bg-blue-50 peer-checked:shadow-lg">
                            <div class="text-3xl font-bold text-alterdata-dark">1</div>
                            <div class="text-sm text-gray-600">minuto</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duracao" value="120" checked class="hidden peer">
                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-alterdata-blue transition peer-checked:border-alterdata-blue peer-checked:bg-blue-50 peer-checked:shadow-lg">
                            <div class="text-3xl font-bold text-alterdata-dark">2</div>
                            <div class="text-sm text-gray-600">minutos</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duracao" value="180" class="hidden peer">
                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-alterdata-blue transition peer-checked:border-alterdata-blue peer-checked:bg-blue-50 peer-checked:shadow-lg">
                            <div class="text-3xl font-bold text-alterdata-dark">3</div>
                            <div class="text-sm text-gray-600">minutos</div>
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="duracao" value="300" class="hidden peer">
                        <div class="border-2 border-gray-300 rounded-xl p-4 text-center hover:border-alterdata-blue transition peer-checked:border-alterdata-blue peer-checked:bg-blue-50 peer-checked:shadow-lg">
                            <div class="text-3xl font-bold text-alterdata-dark">5</div>
                            <div class="text-sm text-gray-600">minutos</div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="bg-alterdata-blue text-white px-12 py-4 rounded-full text-xl font-bold hover:bg-alterdata-dark transform hover:scale-105 transition shadow-lg">
                    Iniciar Simulação
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
