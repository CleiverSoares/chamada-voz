# Documentação de Arquitetura e Fluxo: Simulador de Prospecção Ativa com IA ("Sala de Perigo")

## 1. Visão Geral do Projeto

O projeto é uma aplicação web leve projetada para rodar apresentações ao vivo e treinamentos de prospecção ativa. Ele insere o vendedor em uma simulação de chamada telefônica em tempo real contra uma IA generativa treinada para agir como um cliente real e difícil (Persona).

**Objetivo:** Permitir o treino de quebra de objeções por voz, gerando ao final uma pontuação (score) e uma análise crítica do desempenho do vendedor.

---

## 2. Pilha Tecnológica (Stack)

Para garantir velocidade de entrega e máxima estabilidade no evento, o projeto utiliza o padrão monolítico tradicional com processamento de voz terceirizado:

- **Backend & Roteamento:** Laravel (PHP) no padrão MVC.
- **Banco de Dados:** MySQL ou SQLite (para armazenar histórico e pontuações).
- **Frontend:** Blade Templates com CSS (Tailwind) e JavaScript nativo (Vanilla JS / ES Modules).
- **Motor de IA e Streaming de Voz:** Vapi.ai (integrado via Web SDK no client-side e Webhooks no server-side).

---

## 3. Como a Mágica Funciona (Ciclo de Vida da Simulação)

O funcionamento do sistema é dividido em 4 fases cronológicas. Nenhuma chamada de voz sobrecarrega o servidor do Laravel; todo o peso do streaming de áudio fica no navegador do usuário e nos servidores da Vapi.

### Fase 1: Preparação e Setup (Laravel)

1. O vendedor faz login na aplicação Laravel.
2. Acessa a tela inicial e escolhe a **Persona** que deseja enfrentar (ex: Seu Mário do Varejo).
3. O Laravel cria um registro na tabela `simulacoes` com o status `em_andamento` e redireciona o usuário para a rota da "Arena de Combate".

### Fase 2: O Combate (Navegador + Vapi SDK)

1. A view Blade (`combate.blade.php`) é carregada. O backend injeta na tela a **Chave Pública da Vapi** e o **ID do Assistente** correspondente à persona escolhida.
2. O script JS nativo inicializa o SDK da Vapi no navegador, pedindo permissão de microfone ao usuário.
3. Quando o botão "Iniciar Simulação" é clicado, o SDK abre um túnel WebRTC/WebSocket direto com os servidores da Vapi.ai.

**O Loop de Baixa Latência (Ocorre fora do Laravel):**

- O vendedor fala no microfone.
- A Vapi transcreve o áudio em milissegundos (STT - Speech-to-Text).
- O texto vai para o LLM (GPT-4o-mini), que processa a resposta baseada no System Prompt (personalidade rude/apressada do cliente).
- A resposta do LLM é convertida em áudio ultra-realista (TTS - Text-to-Speech) e tocada no fone do vendedor.
- O JS no Blade apenas escuta eventos do SDK para mudar a interface (exibindo animações de "IA falando", "Você falando" ou atualizando o cronômetro).

### Fase 3: Encerramento e Análise (Vapi Webhook -> Laravel)

1. A chamada é encerrada (porque o tempo limite acabou ou o vendedor clicou em desligar).
2. Nos bastidores, a Vapi compila toda a transcrição da conversa e executa um prompt interno de avaliação para dar uma nota ao vendedor.
3. A Vapi dispara uma requisição POST (Webhook) invisível para a rota de API do seu Laravel (`/api/webhooks/vapi`).
4. O Controller do Laravel recebe o payload JSON contendo a gravação da chamada, a transcrição completa e a análise estruturada (Score, Pontos Positivos, Pontos de Melhoria).
5. O Laravel atualiza o registro da tabela `simulacoes` com esses dados e muda o status para `concluido`.

### Fase 4: O Placar Final (Feedback Visual)

1. O frontend de combate, que estava fazendo um polling leve (ou aguardando o fim da chamada via JS), redireciona o usuário para a tela final de resultados.
2. O Blade consome os dados salvos no banco e exibe o dashboard de desempenho para a plateia ver no telão: nota de 0 a 100 e os conselhos práticos gerados pela IA.

---

## 4. Arquitetura de Integração (O Cérebro na Vapi.ai)

No painel da Vapi.ai, você criará os "Assistentes" (Personas). Cada assistente possui 3 configurações cruciais que ditam o comportamento do projeto:

### Model (LLM)
Configurado com `gpt-4o-mini` para respostas rápidas e custo baixíssimo.

### System Prompt
A instrução de como a IA deve agir.

**Exemplo de Prompt:**
> "Você é o Seu Mário, dono de um supermercado de bairro. Você tem pouco tempo, margem de lucro baixa e acha softwares de gestão caros. Atenda o telefone com pressa. Tente encerrar a ligação rápido. Só aceite agendar uma demonstração se o vendedor focar em falar sobre agilidade no caixa e redução de perdas de estoque."

### Server URL (Webhook)
Apontado para a URL pública do seu projeto Laravel (via Ngrok para testes locais ou domínio de produção) para enviar os relatórios pós-chamada.

---

## 5. Estrutura de Variáveis de Ambiente (.env)

Para o projeto rodar, o Laravel precisa apenas de duas chaves da Vapi configuradas no `.env`:

```env
VAPI_PUBLIC_KEY="pk_live_suachavepublicaaqui"
VAPI_PRIVATE_KEY="sk_live_suachaveprivadaaqui"

# IDs das personas criadas no painel da Vapi
VAPI_ASSISTANT_SEU_MARIO="12345-abcde-id-do-mario"
VAPI_ASSISTANT_DONA_SONIA="67890-fghij-id-da-sonia"
```
