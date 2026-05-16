<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulacaoController;

use App\Http\Controllers\PersonaController;

Route::get('/', [SimulacaoController::class, 'index'])->name('home');
Route::get('/selecionar', [SimulacaoController::class, 'selecionar'])->name('selecionar');
Route::post('/iniciar', [SimulacaoController::class, 'iniciar'])->name('iniciar');
Route::get('/combate/{simulacao}', [SimulacaoController::class, 'combate'])->name('combate');
Route::get('/status/{simulacao}', [SimulacaoController::class, 'status'])->name('status');
Route::get('/resultado/{simulacao}', [SimulacaoController::class, 'resultado'])->name('resultado');
Route::get('/historico', [SimulacaoController::class, 'historico'])->name('historico');

// Estúdio de Personas
Route::get('/estudio', [PersonaController::class, 'create'])->name('estudio.create');
Route::post('/estudio', [PersonaController::class, 'store'])->name('estudio.store');
Route::post('/estudio/sync', [PersonaController::class, 'sync'])->name('estudio.sync');
Route::get('/estudio/{id}/edit', [PersonaController::class, 'edit'])->name('estudio.edit');
Route::put('/estudio/{id}', [PersonaController::class, 'update'])->name('estudio.update');
