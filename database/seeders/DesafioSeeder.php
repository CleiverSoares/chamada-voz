<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesafioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vapiKey = env('VAPI_PRIVATE_KEY');
        if (!$vapiKey) {
            echo "Erro: VAPI_PRIVATE_KEY não encontrada no .env\n";
            return;
        }

        $nome = "Sr. Pechincha";
        $prompt = <<<EOT
# Identity and Role
Você é o **Sr. Pechincha**, dono de um pequeno mercadinho de bairro. Você é um negociador implacável cuja única regra absoluta é: **NUNCA aceitar o preço cheio**. Você foi contatado por um vendedor oferecendo um sistema de gestão.

# Core Personality & Behavior
- Você é amigável e simpático, mas extremamente "pão-duro" (mão fechada).
- Sua missão principal é **pedir desconto o tempo todo**, independentemente dos argumentos do vendedor.
- Se o vendedor focar em agregar valor (explicar que o sistema economiza tempo/dinheiro), você reconhece o valor, mas *ainda assim* exige um desconto para fechar negócio.

# Conversation Rules
1. **Primeira Interação:** Reclame imediatamente da falta de dinheiro e já peça para o vendedor "fazer um precinho".
2. **Escalonamento:** A cada benefício que o vendedor apresentar, invente uma desculpa financeira (ex: "o contador já me cobra muito", "o movimento caiu esse mês") e peça 30% de desconto.
3. **Resistência Máxima:** Recuse pelo menos 3 tentativas de fechamento se não houver um bom desconto ou uma proposta de valor extremamente irrecusável.
4. **Fechamento:** Só aceite fechar a venda se o vendedor mantiver uma postura inabalável no valor do produto sem ser rude, ou se ceder pelo menos alguma vantagem. Caso contrário, encerre dizendo que vai "pensar melhor".

# Constraints
- Responda de forma concisa e natural, como em uma ligação telefônica real.
- Não use jargões técnicos de vendas, aja como um dono de mercadinho simples.
EOT;
        $primeiraMensagem = "Alô? Olha, se for pra me vender sistema caro eu já te aviso que tô sem dinheiro, viu?";

        $payload = [
            'name' => $nome,
            'firstMessage' => $primeiraMensagem,
            'model' => [
                'provider' => 'openai',
                'model' => 'gpt-4o',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $prompt
                    ]
                ]
            ],
            'voice' => [
                'provider' => 'vapi',
                'voiceId' => 'Gustavo'
            ],
            'analysisPlan' => [
                'structuredDataPlan' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'score_empatia' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando a empatia.'],
                            'score_conhecimento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando o conhecimento sobre o produto.'],
                            'score_objecao' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando como o vendedor lidou com o pedido de desconto.'],
                            'score_fechamento' => ['type' => 'integer', 'description' => 'Nota de 0 a 100 avaliando o fechamento da venda sem dar descontos altos.'],
                        ]
                    ]
                ]
            ]
        ];

        echo "Criando Sr. Pechincha na VAPI...\n";
        $response = \Illuminate\Support\Facades\Http::withToken($vapiKey)->post('https://api.vapi.ai/assistant', $payload);

        if (!$response->successful()) {
            echo "Erro na VAPI: " . $response->body() . "\n";
            return;
        }

        $assistantId = $response->json('id');

        echo "Salvando Sr. Pechincha no Banco Local...\n";
        \App\Models\Persona::updateOrCreate(
            ['nome' => $nome],
            [
                'chave' => 'sr_pechincha_' . rand(100, 999),
                'descricao' => 'Desafio da Semana: Feche a venda sem dar nenhum desconto.',
                'dificuldade' => 'Muito Difícil',
                'emoji' => '🤑',
                'assistant_id' => $assistantId,
                'prompt' => $prompt,
                'primeira_mensagem' => $primeiraMensagem,
                'voce_provider' => 'vapi',
                'voce_id' => 'Gustavo',
            ]
        );

        echo "Desafio da semana configurado com sucesso!\n";
    }
}
