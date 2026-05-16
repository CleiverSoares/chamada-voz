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
        Schema::table('simulacoes', function (Blueprint $table) {
            $table->integer('score_empatia')->nullable();
            $table->integer('score_conhecimento')->nullable();
            $table->integer('score_objecao')->nullable();
            $table->integer('score_fechamento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('simulacoes', function (Blueprint $table) {
            $table->dropColumn(['score_empatia', 'score_conhecimento', 'score_objecao', 'score_fechamento']);
        });
    }
};
