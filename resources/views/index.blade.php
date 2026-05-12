@extends('layout')

@section('title', 'Bem-vindo - Sala de Perigo')

@section('content')
<div class="min-h-screen gradient-bg flex items-center justify-center px-6">
    <div class="text-center slide-in">
        <div class="mb-8">
            <svg class="w-32 h-32 mx-auto text-white mb-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
            <h1 class="text-6xl font-bold text-white mb-4">Sala de Perigo</h1>
            <p class="text-2xl text-blue-100 mb-2">Treinamento de Prospecção Ativa com IA</p>
            <p class="text-lg text-blue-200">Enfrente clientes difíceis e aprimore suas habilidades</p>
        </div>

        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 max-w-2xl mx-auto mb-8">
            <h2 class="text-2xl font-semibold text-white mb-4">Como Funciona?</h2>
            <div class="grid md:grid-cols-3 gap-6 text-white">
                <div class="text-center">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl font-bold">1</span>
                    </div>
                    <h3 class="font-semibold mb-2">Escolha a Persona</h3>
                    <p class="text-sm text-blue-100">Selecione o cliente que deseja enfrentar</p>
                </div>
                <div class="text-center">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl font-bold">2</span>
                    </div>
                    <h3 class="font-semibold mb-2">Entre na Arena</h3>
                    <p class="text-sm text-blue-100">Converse por voz com a IA em tempo real</p>
                </div>
                <div class="text-center">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <span class="text-3xl font-bold">3</span>
                    </div>
                    <h3 class="font-semibold mb-2">Receba Feedback</h3>
                    <p class="text-sm text-blue-100">Veja sua pontuação e pontos de melhoria</p>
                </div>
            </div>
        </div>

        <a href="{{ route('selecionar') }}" class="inline-block bg-white text-alterdata-blue px-12 py-4 rounded-full text-xl font-bold hover:bg-blue-50 transform hover:scale-105 transition shadow-2xl">
            Começar Treinamento
        </a>
    </div>
</div>
@endsection
