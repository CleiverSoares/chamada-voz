<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulacao extends Model
{
    protected $table = 'simulacoes';

    protected $fillable = [
        'persona',
        'vendedor_nome',
        'status',
        'score',
        'transcricao',
        'pontos_positivos',
        'pontos_melhoria',
        'call_id',
        'recording_url',
        'duracao_segundos',
    ];

    protected $casts = [
        'score' => 'integer',
        'duracao_segundos' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    // Forçar timezone de São Paulo
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return $date->setTimezone(new \DateTimeZone('America/Sao_Paulo'))->format('Y-m-d H:i:s');
    }
}
