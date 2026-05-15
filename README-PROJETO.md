# 🎯 Sala de Perigo - Simulador de Prospecção Ativa com IA

Sistema de treinamento de vendas com IA conversacional em tempo real, desenvolvido para a Alterdata Software.

## 🚀 Tecnologias

- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade Templates + Tailwind CSS + JavaScript Vanilla
- **Banco de Dados:** SQLite (desenvolvimento) / MySQL (produção)
- **IA & Voz:** Vapi.ai (GPT-4o-mini + TTS/STT)

## 📋 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM (para assets)
- Conta na [Vapi.ai](https://vapi.ai)

## ⚙️ Instalação

### 1. Clone e instale dependências

```bash
# Instalar dependências PHP
composer install

# Instalar dependências Node
npm install
```

### 2. Configure o ambiente

```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configure as variáveis da Vapi no `.env`

```env
VAPI_PUBLIC_KEY="sua_chave_publica_aqui"
VAPI_PRIVATE_KEY="sua_chave_privada_aqui"

# IDs dos assistentes criados no painel da Vapi
VAPI_ASSISTANT_SEU_ANTONIO="id_do_assistente_mario"
VAPI_ASSISTANT_DONA_SONIA="id_do_assistente_sonia"
```

### 4. Configure o banco de dados

```bash
# Criar banco SQLite (já configurado no .env)
touch database/database.sqlite

# Executar migrations
php artisan migrate
```

### 5. Inicie o servidor

```bash
# Servidor Laravel
php artisan serve

# Em outro terminal, compile assets (se necessário)
npm run dev
```

Acesse: `http://localhost:8000`

## 🎭 Configuração das Personas na Vapi.ai

### 1. Acesse o painel da Vapi.ai

Vá para [dashboard.vapi.ai](https://dashboard.vapi.ai) e faça login.

### 2. Crie os Assistentes

#### Assistente 1: Antônio

**Configurações:**
- **Nome:** Antônio - Supermercado
- **Model:** gpt-4o-mini
- **Voice:** Escolha uma voz masculina em português (ex: pt-BR-AntonioNeural)

**System Prompt:**
```
Você é o Antônio, dono de um supermercado de bairro há 15 anos. 

PERSONALIDADE:
- Você é apressado, desconfiado e direto
- Tem pouco tempo para conversas de vendas
- Já foi enganado por vendedores antes
- Margem de lucro é apertada, cada centavo conta
- Acha que software de gestão é caro e complicado

COMPORTAMENTO NA LIGAÇÃO:
- Atenda com pressa: "Alô, é rápido? Tô no meio do expediente"
- Tente encerrar a ligação rapidamente
- Faça objeções sobre preço e complexidade
- Só demonstre interesse se o vendedor focar em:
  * Agilidade no caixa
  * Redução de perdas de estoque
  * Facilidade de uso (sem treinamento complicado)
  * ROI rápido e mensurável

OBJEÇÕES COMUNS:
- "Tá muito caro"
- "Não tenho tempo pra aprender sistema novo"
- "Meu caderninho funciona bem"
- "Já tentei software antes e não deu certo"

Seja realista e difícil, mas não impossível. Se o vendedor for bom, aceite agendar uma demonstração.
```

**Server URL (Webhook):**
```
https://seu-dominio.com/api/webhooks/vapi
```
(Use ngrok para testes locais: `ngrok http 8000`)

#### Assistente 2: Sônia

**Configurações:**
- **Nome:** Sônia - Loja de Roupas
- **Model:** gpt-4o-mini
- **Voice:** Escolha uma voz feminina em português (ex: pt-BR-FranciscaNeural)

**System Prompt:**
```
Você é a Sônia, gerente de uma loja de roupas femininas no shopping.

PERSONALIDADE:
- Cética com tecnologia ("não entendo dessas coisas")
- Preocupada com treinamento da equipe
- Tem medo de perder controle do negócio
- Valoriza atendimento pessoal ao cliente
- Acha que sistema vai "robotizar" a loja

COMPORTAMENTO NA LIGAÇÃO:
- Atenda educada mas desconfiada
- Faça muitas perguntas sobre dificuldade de uso
- Preocupe-se com a equipe: "Minhas meninas vão saber usar?"
- Questione se vai atrapalhar o atendimento

SÓ DEMONSTRE INTERESSE SE:
- O vendedor explicar de forma MUITO simples
- Falar sobre suporte e treinamento incluído
- Mostrar como ajuda (não atrapalha) o atendimento
- Dar exemplos de outras lojas similares

OBJEÇÕES COMUNS:
- "Isso é muito complicado pra mim"
- "Minha equipe não vai saber usar"
- "Vai atrapalhar o atendimento dos clientes"
- "E se der problema? Quem resolve?"

Seja ainda mais difícil que o Antônio. Exija paciência e didática do vendedor.
```

**Server URL (Webhook):**
```
https://seu-dominio.com/api/webhooks/vapi
```

### 3. Copie os IDs dos Assistentes

Após criar cada assistente, copie o ID (formato: `xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`) e cole no `.env`:

```env
VAPI_ASSISTANT_SEU_ANTONIO="id-copiado-do-painel"
VAPI_ASSISTANT_DONA_SONIA="id-copiado-do-painel"
```

## 🔗 Configuração do Webhook (Ngrok para testes)

Para receber os webhooks da Vapi em desenvolvimento local:

```bash
# Instale o ngrok
# https://ngrok.com/download

# Inicie o túnel
ngrok http 8000

# Copie a URL gerada (ex: https://abc123.ngrok.io)
# Cole no campo "Server URL" de cada assistente na Vapi:
# https://abc123.ngrok.io/api/webhooks/vapi
```

## 📱 Como Usar

1. **Acesse a aplicação** em `http://localhost:8000`
2. **Clique em "Começar Treinamento"**
3. **Digite seu nome** e **selecione uma persona**
4. **Clique em "Iniciar Simulação"**
5. **Permita o acesso ao microfone** quando solicitado
6. **Clique em "Iniciar Chamada"** e comece a falar
7. **Converse naturalmente** com a IA
8. **Clique em "Encerrar Chamada"** quando terminar
9. **Veja seu resultado** com pontuação e feedback

## 🎨 Personalização de Cores

O projeto usa as cores da Alterdata:
- **Azul Principal:** `#0066CC`
- **Azul Escuro:** `#003D7A`
- **Azul Claro:** `#4D94FF`

Para alterar, edite as cores no arquivo `resources/views/layout.blade.php`:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'alterdata-blue': '#0066CC',
                'alterdata-dark': '#003D7A',
                'alterdata-light': '#4D94FF',
            }
        }
    }
}
```

## 🐛 Troubleshooting

### Erro: "Vapi is not defined"

Verifique se o script da Vapi está carregando:
```html
<script src="https://cdn.jsdelivr.net/npm/vapi-web-sdk@latest/dist/index.js"></script>
```

### Webhook não está sendo recebido

1. Verifique se o ngrok está rodando
2. Confirme a URL no painel da Vapi
3. Veja os logs: `tail -f storage/logs/laravel.log`

### Microfone não funciona

1. Verifique permissões do navegador
2. Use HTTPS (ngrok já fornece)
3. Teste em navegador diferente (Chrome recomendado)

## 📊 Estrutura do Banco de Dados

### Tabela: `simulacoes`

| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | bigint | ID único |
| persona | string | Persona escolhida |
| vendedor_nome | string | Nome do vendedor |
| status | enum | em_andamento, concluido, cancelado |
| score | integer | Pontuação (0-100) |
| transcricao | text | Transcrição completa |
| pontos_positivos | text | Feedback positivo |
| pontos_melhoria | text | Pontos a melhorar |
| call_id | string | ID da chamada na Vapi |
| recording_url | string | URL da gravação |
| duracao_segundos | integer | Duração em segundos |
| created_at | timestamp | Data de criação |
| updated_at | timestamp | Data de atualização |

## 🚀 Deploy em Produção

### Requisitos

- Servidor com PHP 8.2+
- MySQL ou PostgreSQL
- Domínio com SSL (obrigatório para microfone)

### Passos

1. Configure o `.env` para produção:
```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
# ... outras configs
```

2. Otimize a aplicação:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Configure o webhook na Vapi com a URL de produção:
```
https://seudominio.com/api/webhooks/vapi
```

## 📄 Licença

Projeto desenvolvido para Alterdata Software.

## 🤝 Suporte

Para dúvidas ou problemas, entre em contato com a equipe de desenvolvimento.

---

**Desenvolvido com ❤️ para Alterdata Software**
