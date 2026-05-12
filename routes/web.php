<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulacaoController;

Route::get('/', [SimulacaoController::class, 'index'])->name('home');
Route::get('/selecionar', [SimulacaoController::class, 'selecionar'])->name('selecionar');
Route::post('/iniciar', [SimulacaoController::class, 'iniciar'])->name('iniciar');
Route::get('/combate/{simulacao}', [SimulacaoController::class, 'combate'])->name('combate');
Route::get('/status/{simulacao}', [SimulacaoController::class, 'status'])->name('status');
Route::get('/resultado/{simulacao}', [SimulacaoController::class, 'resultado'])->name('resultado');
Route::get('/historico', [SimulacaoController::class, 'historico'])->name('historico');
