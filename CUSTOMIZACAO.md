# 🎨 Guia de Customização - Sala de Perigo

## 🎭 Adicionar Novas Personas

### Passo 1: Criar Assistente na Vapi

1. Acesse o dashboard da Vapi
2. Clique em **Create Assistant**
3. Configure a nova persona

**Exemplo: "Dr. Carlos - Médico Ocupado"**

```
Nome: Dr. Carlos
Model: gpt-4o-mini
Voice: pt-BR-AntonioNeural (ou outra voz masculina)

System Prompt:
Você é o Dr. Carlos, médico de uma clínica particular.

PERSONALIDADE:
- Extremamente ocupado (atende 40 pacientes/dia)
- Cético com tecnologia ("já uso sistema do convênio")
- Preocupado com LGPD e segurança de dados
- Valoriza eficiência acima de tudo

COMPORTAMENTO:
- Atenda apressado: "Alô, tenho 2 minutos antes da próxima consulta"
- Faça objeções sobre segurança e compliance
- Questione integração com sistemas existentes
- Só demonstre interesse se falar de:
  * Segurança e LGPD
  * Integração com sistemas médicos
  * Economia de tempo real
  * Suporte técnico 24/7

OBJEÇÕES:
- "Já tenho sistema do convênio"
- "E a segurança dos dados dos pacientes?"
- "Não tenho tempo pra migração"
- "Precisa de treinamento?"
```

### Passo 2: Adicionar no .env

```env
VAPI_ASSISTANT_DR_CARLOS="id-do-assistente-aqui"
```

### Passo 3: Atualizar o Controller

Edite `app/Http/Controllers/SimulacaoController.php`:

```php
public function selecionar()
{
    $personas = [
        'seu_mario' => [
            'nome' => 'Seu Mário',
            'descricao' => 'Dono de supermercado de bairro. Apressado, desconfiado e focado em custos.',
            'dificuldade' => 'Difícil',
            'assistant_id' => env('VAPI_ASSISTANT_SEU_MARIO'),
        ],
        'dona_sonia' => [
            'nome' => 'Dona Sônia',
            'descricao' => 'Gerente de loja de roupas. Cética com tecnologia e preocupada com treinamento.',
            'dificuldade' => 'Muito Difícil',
            'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
        ],
        // ADICIONE AQUI
        'dr_carlos' => [
            'nome' => 'Dr. Carlos',
            'descricao' => 'Médico de clínica particular. Ocupado, preocupado com LGPD e segurança.',
            'dificuldade' => 'Extremo',
            'assistant_id' => env('VAPI_ASSISTANT_DR_CARLOS'),
        ],
    ];

    return view('selecionar', compact('personas'));
}
```

E também no método `combate()`:

```php
public function combate(Simulacao $simulacao)
{
    // ... código existente ...
    
    $personas = [
        'seu_mario' => [
            'nome' => 'Seu Mário',
            'assistant_id' => env('VAPI_ASSISTANT_SEU_MARIO'),
        ],
        'dona_sonia' => [
            'nome' => 'Dona Sônia',
            'assistant_id' => env('VAPI_ASSISTANT_DONA_SONIA'),
        ],
        // ADICIONE AQUI
        'dr_carlos' => [
            'nome' => 'Dr. Carlos',
            'assistant_id' => env('VAPI_ASSISTANT_DR_CARLOS'),
        ],
    ];
    
    // ... resto do código ...
}
```

Pronto! A nova persona aparecerá automaticamente na tela de seleção.

---

## 🎨 Personalizar Cores e Branding

### Opção 1: Editar Diretamente no Layout

Edite `resources/views/layout.blade.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                // Cores da Alterdata (padrão)
                'alterdata-blue': '#0066CC',
                'alterdata-dark': '#003D7A',
                'alterdata-light': '#4D94FF',
                
                // OU use cores de outra empresa:
                // 'primary': '#FF6B35',
                // 'primary-dark': '#CC5529',
                // 'primary-light': '#FF8C61',
            }
        }
    }
}
```

### Opção 2: Criar Tema Customizado

Crie `resources/css/custom-theme.css`:

```css
:root {
    --color-primary: #0066CC;
    --color-primary-dark: #003D7A;
    --color-primary-light: #4D94FF;
    --color-success: #10B981;
    --color-warning: #F59E0B;
    --color-danger: #EF4444;
}

.gradient-bg {
    background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
}

.btn-primary {
    background-color: var(--color-primary);
    color: white;
}

.btn-primary:hover {
    background-color: var(--color-primary-dark);
}
```

Importe no layout:

```html
<link rel="stylesheet" href="{{ asset('css/custom-theme.css') }}">
```

---

## 🖼️ Adicionar Logo Personalizado

### Passo 1: Adicionar a imagem

Coloque o logo em `public/images/logo.png`

### Passo 2: Atualizar o Layout

Edite `resources/views/layout.blade.php`:

```html
<div class="flex items-center space-x-4">
    <!-- Substitua o SVG por: -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10">
    <div>
        <h1 class="text-2xl font-bold">Sala de Perigo</h1>
        <p class="text-xs text-blue-200">Powered by Alterdata</p>
    </div>
</div>
```

---

## ⚙️ Customizar Sistema de Pontuação

### Modificar Critérios de Avaliação

Edite o System Prompt dos assistentes na Vapi para incluir critérios específicos:

```
Ao final da chamada, avalie o vendedor nos seguintes critérios:

1. ABERTURA (0-20 pontos)
   - Apresentação clara
   - Pediu permissão para falar
   - Criou rapport

2. DESCOBERTA (0-20 pontos)
   - Fez perguntas relevantes
   - Ouviu ativamente
   - Identificou dores

3. ARGUMENTAÇÃO (0-30 pontos)
   - Focou em benefícios (não features)
   - Usou dados e exemplos
   - Personalizou a solução

4. QUEBRA DE OBJEÇÕES (0-20 pontos)
   - Respondeu com empatia
   - Usou técnicas adequadas
   - Não foi defensivo

5. FECHAMENTO (0-10 pontos)
   - Propôs próximo passo claro
   - Criou senso de urgência
   - Confirmou compromisso

FORMATO DA RESPOSTA:
{
  "score": [soma dos pontos],
  "pontos_positivos": "[lista 3-5 pontos específicos]",
  "pontos_melhoria": "[lista 3-5 ações concretas]"
}
```

---

## 📊 Adicionar Relatórios e Dashboards

### Criar Página de Histórico

1. **Criar rota** em `routes/web.php`:

```php
Route::get('/historico', [SimulacaoController::class, 'historico'])->name('historico');
```

2. **Adicionar método** em `SimulacaoController.php`:

```php
public function historico()
{
    $simulacoes = Simulacao::orderBy('created_at', 'desc')
        ->paginate(20);
    
    $estatisticas = [
        'total' => Simulacao::count(),
        'media_score' => Simulacao::avg('score'),
        'melhor_score' => Simulacao::max('score'),
        'total_tempo' => Simulacao::sum('duracao_segundos'),
    ];
    
    return view('historico', compact('simulacoes', 'estatisticas'));
}
```

3. **Criar view** `resources/views/historico.blade.php`:

```blade
@extends('layout')

@section('title', 'Histórico de Simulações')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h1 class="text-4xl font-bold text-alterdata-dark mb-8">Histórico de Simulações</h1>
    
    <!-- Estatísticas -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-3xl font-bold text-alterdata-blue">{{ $estatisticas['total'] }}</div>
            <div class="text-gray-600">Total de Simulações</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-3xl font-bold text-green-600">{{ number_format($estatisticas['media_score'], 1) }}</div>
            <div class="text-gray-600">Média de Pontuação</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $estatisticas['melhor_score'] }}</div>
            <div class="text-gray-600">Melhor Pontuação</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <div class="text-3xl font-bold text-purple-600">{{ gmdate('H:i', $estatisticas['total_tempo']) }}</div>
            <div class="text-gray-600">Tempo Total</div>
        </div>
    </div>
    
    <!-- Tabela -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-alterdata-blue text-white">
                <tr>
                    <th class="px-6 py-4 text-left">Vendedor</th>
                    <th class="px-6 py-4 text-left">Persona</th>
                    <th class="px-6 py-4 text-center">Score</th>
                    <th class="px-6 py-4 text-center">Data</th>
                    <th class="px-6 py-4 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($simulacoes as $sim)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $sim->vendedor_nome }}</td>
                    <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $sim->persona)) }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-block px-3 py-1 rounded-full font-bold
                            {{ $sim->score >= 80 ? 'bg-green-100 text-green-700' : ($sim->score >= 60 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $sim->score }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">{{ $sim->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('resultado', $sim->id) }}" class="text-alterdata-blue hover:underline">
                            Ver Detalhes
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $simulacoes->links() }}
    </div>
</div>
@endsection
```

4. **Adicionar link no menu** em `layout.blade.php`:

```html
<a href="{{ route('historico') }}" class="hover:text-blue-200 transition">
    Histórico
</a>
```

---

## 🔊 Customizar Vozes

### Vozes Disponíveis (Azure TTS)

**Português do Brasil:**

| Voz | Gênero | Estilo |
|-----|--------|--------|
| pt-BR-AntonioNeural | Masculino | Neutro |
| pt-BR-FranciscaNeural | Feminino | Neutro |
| pt-BR-BrendaNeural | Feminino | Jovem |
| pt-BR-DonatoNeural | Masculino | Maduro |
| pt-BR-ElzaNeural | Feminino | Profissional |
| pt-BR-FabioNeural | Masculino | Jovem |
| pt-BR-GiovannaNeural | Feminino | Jovem |
| pt-BR-HumbertoNeural | Masculino | Maduro |
| pt-BR-JulioNeural | Masculino | Profissional |
| pt-BR-LeilaNeural | Feminino | Profissional |
| pt-BR-LeticiaNeural | Feminino | Jovem |
| pt-BR-ManuelaNeural | Feminino | Idosa |
| pt-BR-NicolauNeural | Masculino | Jovem |
| pt-BR-ValerioNeural | Masculino | Maduro |
| pt-BR-YaraNeural | Feminino | Jovem |

Configure no assistente da Vapi em **Voice Settings**.

---

## 🎯 Adicionar Níveis de Dificuldade

### Criar Variações da Mesma Persona

**Exemplo: Seu Mário - Fácil, Médio, Difícil**

1. Crie 3 assistentes na Vapi
2. Ajuste o System Prompt:

**Fácil:**
```
Você é receptivo e faz poucas objeções.
Aceite a proposta se o vendedor for minimamente convincente.
```

**Médio:**
```
Você é cético mas aberto.
Faça 2-3 objeções mas aceite bons argumentos.
```

**Difícil:**
```
Você é extremamente resistente.
Faça muitas objeções e só aceite argumentos excepcionais.
```

3. Adicione no controller:

```php
'seu_mario_facil' => [...],
'seu_mario_medio' => [...],
'seu_mario_dificil' => [...],
```

---

## 📱 Tornar Responsivo para Mobile

O design já é responsivo, mas para melhorar:

### Adicionar Meta Tags

Em `layout.blade.php`:

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
```

### Otimizar Botões para Touch

```css
.btn {
    min-height: 48px;
    min-width: 48px;
    touch-action: manipulation;
}
```

---

## 🔐 Adicionar Autenticação

### Usar Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
php artisan migrate
```

### Proteger Rotas

Em `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/selecionar', [SimulacaoController::class, 'selecionar'])->name('selecionar');
    Route::post('/iniciar', [SimulacaoController::class, 'iniciar'])->name('iniciar');
    // ... outras rotas protegidas
});
```

---

## 📧 Enviar Resultados por Email

### Adicionar ao Controller

```php
use Illuminate\Support\Facades\Mail;

public function resultado(Simulacao $simulacao)
{
    if ($simulacao->status === 'concluido') {
        // Enviar email
        Mail::to($simulacao->vendedor_email)->send(
            new ResultadoSimulacao($simulacao)
        );
    }
    
    return view('resultado', compact('simulacao'));
}
```

---

## 🎮 Gamificação

### Adicionar Badges e Conquistas

1. Criar migration para badges
2. Definir conquistas:
   - 🥉 Primeira Simulação
   - 🥈 10 Simulações
   - 🥇 Score acima de 90
   - 🏆 Venceu todas as personas

3. Exibir na tela de resultado

---

**Essas são as principais customizações possíveis!**

Para dúvidas específicas, consulte a documentação do Laravel e da Vapi.ai.

*Desenvolvido para Alterdata Software* 💙
