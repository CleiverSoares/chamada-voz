<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique()->comment('Identificador interno (ex: seu_mario)');
            $table->string('nome');
            $table->text('descricao');
            $table->string('dificuldade');
            $table->string('emoji')->default('🎯');
            $table->string('assistant_id');
            $table->text('prompt')->nullable();
            $table->string('voce_provider')->nullable()->default('vapi');
            $table->string('voce_id')->nullable();
            $table->text('primeira_mensagem')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
