# 🚀 Início Rápido - Sala de Perigo

## ✅ Checklist de Configuração

### 1. Banco de Dados
- [x] Migrations executadas
- [x] SQLite configurado

### 2. Configurar Vapi.ai (IMPORTANTE!)

#### Passo 1: Criar conta na Vapi
1. Acesse: https://vapi.ai
2. Crie uma conta gratuita
3. Acesse o dashboard: https://dashboard.vapi.ai

#### Passo 2: Obter as chaves de API
1. No dashboard, vá em **Settings** → **API Keys**
2. Copie a **Public Key** (começa com `pk_`)
3. Copie a **Private Key** (começa com `sk_`)
4. Cole no arquivo `.env`:

```env
VAPI_PUBLIC_KEY="sua_public_key_aqui"
VAPI_PRIVATE_KEY="sua_private_key_aqui"
```

#### Passo 3: Criar os Assistentes (Personas)

##### Assistente 1: Antônio

1. No dashboard da Vapi, clique em **Create Assistant**
2. Preencha:
   - **Name:** Antônio
   - **Model:** gpt-4o-mini
   - **Voice:** Escolha uma voz masculina em português (ex: `pt-BR-AntonioNeural`)
   
3. **System Prompt** (cole exatamente):

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

4. **Server URL:** Deixe em branco por enquanto (configuraremos depois)
5. Clique em **Save**
6. **COPIE O ID DO ASSISTENTE** (aparece na URL ou na lista)
7. Cole no `.env`:

```env
VAPI_ASSISTANT_SEU_ANTONIO="id_copiado_aqui"
```

##### Assistente 2: Sônia

Repita o processo acima com:

- **Name:** Sônia
- **Model:** gpt-4o-mini
- **Voice:** Voz feminina em português (ex: `pt-BR-FranciscaNeural`)

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

Cole o ID no `.env`:

```env
VAPI_ASSISTANT_DONA_SONIA="id_copiado_aqui"
```

### 3. Configurar Webhook (Para receber resultados)

#### Opção A: Desenvolvimento Local com Ngrok

1. **Instale o Ngrok:**
   - Acesse: https://ngrok.com/download
   - Baixe e instale

2. **Inicie o túnel:**
```bash
ngrok http 8000
```

3. **Copie a URL gerada** (ex: `https://abc123.ngrok-free.app`)

4. **Configure nos assistentes da Vapi:**
   - Edite cada assistente
   - Em **Server URL**, cole:
   ```
   https://sua-url-ngrok.ngrok-free.app/api/webhooks/vapi
   ```
   - Salve

#### Opção B: Produção

Use sua URL de produção:
```
https://seudominio.com/api/webhooks/vapi
```

### 4. Iniciar o Servidor

```bash
php artisan serve
```

Acesse: **http://localhost:8000**

## 🎮 Como Testar

1. Abra o navegador em `http://localhost:8000`
2. Clique em **"Começar Treinamento"**
3. Digite seu nome
4. Selecione **"Antônio"**
5. Clique em **"Iniciar Simulação"**
6. Permita o acesso ao microfone
7. Clique em **"Iniciar Chamada"**
8. **Fale naturalmente** como se estivesse vendendo um software
9. Clique em **"Encerrar Chamada"**
10. Veja seu resultado!

## 🎯 Dicas para um Bom Desempenho

### Com o Antônio:
- Seja direto e objetivo
- Foque em economia de tempo e dinheiro
- Mencione ROI rápido
- Fale sobre facilidade de uso
- Ofereça demonstração prática

### Com a Sônia:
- Seja paciente e didático
- Explique de forma simples
- Fale sobre suporte e treinamento
- Dê exemplos de outras lojas
- Mostre como ajuda (não complica)

## ⚠️ Problemas Comuns

### "Vapi is not defined"
- Verifique sua conexão com a internet
- O CDN da Vapi pode estar bloqueado

### Microfone não funciona
- Use Chrome ou Edge (melhor compatibilidade)
- Verifique permissões do navegador
- Use HTTPS (ngrok já fornece)

### Webhook não recebe dados
- Verifique se o ngrok está rodando
- Confirme a URL nos assistentes da Vapi
- Veja os logs: `tail -f storage/logs/laravel.log`

### Assistente não responde
- Verifique se o ID está correto no `.env`
- Confirme que o assistente está ativo na Vapi
- Verifique se tem créditos na conta Vapi

## 📊 Verificar se está funcionando

### Teste 1: Página inicial carrega?
✅ Deve mostrar "Sala de Perigo" com botão azul

### Teste 2: Seleção de persona funciona?
✅ Deve mostrar cards do Antônio e Sônia

### Teste 3: Arena de combate abre?
✅ Deve pedir permissão de microfone

### Teste 4: Chamada conecta?
✅ Deve mostrar "Chamada Ativa" e começar a transcrever

### Teste 5: Resultado aparece?
✅ Deve mostrar pontuação e feedback após encerrar

## 🆘 Precisa de Ajuda?

1. Verifique os logs do Laravel:
```bash
tail -f storage/logs/laravel.log
```

2. Verifique o console do navegador (F12)

3. Teste a API da Vapi diretamente no dashboard

4. Confirme que todas as variáveis do `.env` estão preenchidas

## 🎉 Pronto!

Se tudo estiver configurado corretamente, você terá um sistema completo de treinamento de vendas com IA funcionando!

---

**Desenvolvido para Alterdata Software** 💙
