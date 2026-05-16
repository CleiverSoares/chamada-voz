<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';

    protected $fillable = [
        'chave',
        'nome',
        'descricao',
        'dificuldade',
        'emoji',
        'assistant_id',
        'prompt',
        'voce_provider',
        'voce_id',
        'primeira_mensagem',
    ];
}
