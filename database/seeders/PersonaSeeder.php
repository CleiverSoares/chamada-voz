<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Persona;

class PersonaSeeder extends Seeder
{
    public function run(): void
    {
        $personas = [
            [
                'chave' => 'seu_mario',
                'nome' => 'Antônio',
                'descricao' => 'Dono de supermercado de bairro. Apressado, desconfiado e focado em custos.',
                'dificuldade' => 'Difícil',
                'emoji' => '🏪',
                'assistant_id' => env('VAPI_ASSISTANT_SEU_ANTONIO'),
            ],
            [
                'chave' => 'dona_sonia',
                'nome' => 'Sônia',
                'descricao' => 'Contadora de 58 anos. Metódica, educada, mas resistente a mudanças. Usa vários sistemas separados.',
                'dificuldade' => 'Muito Difícil',
                'emoji' => '👩‍💼',
                'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
            ],
            [
                'chave' => 'flavio_academia_vendas',
                'nome' => 'Flávio — Mentor Elite',
                'descricao' => 'Mentor da Academia de Vendas Alterdata. Treina pitch, simula clientes sob demanda e dá feedback cirúrgico em tempo real.',
                'dificuldade' => 'Treinamento',
                'emoji' => '🏆',
                'assistant_id' => env('VAPI_ASSISTANT_FLAVIO_ACAD_VENDAS'),
            ],
        ];

        foreach ($personas as $persona) {
            Persona::updateOrCreate(
                ['chave' => $persona['chave']],
                $persona
            );
        }
    }
}
