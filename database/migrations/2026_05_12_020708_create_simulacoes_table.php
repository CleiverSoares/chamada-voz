<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('simulacoes', function (Blueprint $table) {
            $table->id();
            $table->string('persona');
            $table->string('vendedor_nome');
            $table->enum('status', ['em_andamento', 'concluido', 'cancelado'])->default('em_andamento');
            $table->integer('score')->nullable();
            $table->text('transcricao')->nullable();
            $table->text('pontos_positivos')->nullable();
            $table->text('pontos_melhoria')->nullable();
            $table->string('call_id')->nullable();
            $table->string('recording_url')->nullable();
            $table->integer('duracao_segundos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('simulacoes');
    }
};
