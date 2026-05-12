import Vapi from '@vapi-ai/web';

// Configuração será injetada pelo Blade
const config = window.vapiConfig || {};

const VAPI_PUBLIC_KEY = config.publicKey;
const ASSISTANT_ID = config.assistantId;
const SIMULACAO_ID = config.simulacaoId;
const DURACAO_MAXIMA = config.duracaoMaxima || 120; // segundos

let vapi;
let startTime;
let timerInterval;
let tempoDecorrido = 0;

// Elementos
const btnStart = document.getElementById('btn-start');
const btnEnd = document.getElementById('btn-end');
const statusText = document.getElementById('status-text');
const statusDescription = document.getElementById('status-description');
const timerEl = document.getElementById('timer');
const timerContainer = document.getElementById('timer-container');
const timerInfo = document.getElementById('timer-info');
const transcriptEl = document.getElementById('transcript');
const iconWaiting = document.getElementById('icon-waiting');
const iconSpeaking = document.getElementById('icon-speaking');
const iconListening = document.getElementById('icon-listening');

// Inicializar Vapi
try {
    vapi = new Vapi(VAPI_PUBLIC_KEY);
    console.log('✅ Vapi inicializado com sucesso');
} catch (error) {
    console.error('❌ Erro ao inicializar Vapi:', error);
    alert('Erro ao carregar o sistema de voz. Verifique as configurações.');
}

// Iniciar chamada
btnStart.addEventListener('click', async () => {
    if (!vapi) {
        alert('Sistema de voz não está disponível. Recarregue a página.');
        return;
    }
    
    try {
        console.log('🎤 Iniciando chamada com assistente:', ASSISTANT_ID);
        await vapi.start(ASSISTANT_ID);
        btnStart.classList.add('hidden');
        btnEnd.classList.remove('hidden');
        startTimer();
        updateStatus('Conectando...', 'Estabelecendo conexão com a IA', 'listening');
    } catch (error) {
        console.error('❌ Erro ao iniciar:', error);
        alert('Erro ao iniciar a chamada: ' + error.message + '\n\nVerifique:\n1. Chave pública da Vapi no .env\n2. ID do assistente configurado\n3. Permissão do microfone');
    }
});

// Encerrar chamada
btnEnd.addEventListener('click', () => {
    encerrarChamada();
});

function encerrarChamada() {
    if (!vapi) {
        alert('Sistema de voz não está disponível.');
        return;
    }
    
    try {
        vapi.stop();
        stopTimer();
        updateStatus('Encerrando...', 'Processando análise do desempenho', 'waiting');
        btnEnd.disabled = true;
        
        // Aguardar resultado e redirecionar automaticamente
        aguardarResultado();
    } catch (error) {
        console.error('❌ Erro ao encerrar:', error);
        alert('Erro ao encerrar a chamada: ' + error.message);
    }
}

function aguardarResultado() {
    let tentativas = 0;
    const maxTentativas = 20; // 40 segundos (20 x 2s)
    
    console.log('⏳ Aguardando resultado da análise...');
    
    const checkInterval = setInterval(() => {
        tentativas++;
        console.log(`🔍 Verificando resultado... (tentativa ${tentativas}/${maxTentativas})`);
        
        fetch(`/status/${SIMULACAO_ID}`)
            .then(r => r.json())
            .then(data => {
                console.log('📊 Status:', data);
                
                if (data.status === 'concluido') {
                    clearInterval(checkInterval);
                    console.log('✅ Resultado pronto! Redirecionando em 2 segundos...');
                    
                    // Feedback visual
                    updateStatus('✅ Análise Concluída!', 'Redirecionando para o resultado...', 'waiting');
                    
                    // Aguarda 2 segundos para o usuário ver
                    setTimeout(() => {
                        console.log('🚀 Redirecionando agora...');
                        window.location.href = `/resultado/${SIMULACAO_ID}`;
                    }, 2000);
                } else if (tentativas >= maxTentativas) {
                    clearInterval(checkInterval);
                    console.log('⏱️ Timeout - Redirecionando mesmo assim...');
                    window.location.href = `/resultado/${SIMULACAO_ID}`;
                }
            })
            .catch(error => {
                console.error('❌ Erro ao verificar status:', error);
                if (tentativas >= maxTentativas) {
                    clearInterval(checkInterval);
                    window.location.href = `/resultado/${SIMULACAO_ID}`;
                }
            });
    }, 2000);
}

// Event listeners da Vapi
if (vapi) {
    vapi.on('call-start', () => {
        console.log('📞 Chamada iniciada');
        updateStatus('Chamada Ativa', 'Você está falando com a IA', 'speaking');
        transcriptEl.innerHTML = '';
    });
    
    vapi.on('speech-start', () => {
        console.log('🤖 IA começou a falar');
        updateStatus('IA Falando', 'Ouça atentamente a resposta', 'listening');
    });
    
    vapi.on('speech-end', () => {
        console.log('🎤 IA parou de falar');
        updateStatus('Sua Vez', 'Fale agora no microfone', 'speaking');
    });
    
    vapi.on('message', (message) => {
        console.log('💬 Mensagem recebida:', message);
        if (message.type === 'transcript' && message.transcript) {
            const role = message.role === 'user' ? 'Você' : 'Cliente';
            const color = message.role === 'user' ? 'text-blue-300' : 'text-green-300';
            transcriptEl.innerHTML += `<p><span class="${color} font-bold">${role}:</span> ${message.transcript}</p>`;
            transcriptEl.scrollTop = transcriptEl.scrollHeight;
        }
    });
    
    vapi.on('call-end', () => {
        console.log('📴 Chamada encerrada');
        updateStatus('Chamada Encerrada', 'Aguarde o resultado...', 'waiting');
        stopTimer();
    });
    
    vapi.on('error', (error) => {
        console.error('❌ Erro Vapi:', error);
        updateStatus('Erro', 'Ocorreu um problema na chamada', 'waiting');
        alert('Erro na chamada: ' + (error.message || 'Erro desconhecido'));
    });
} else {
    console.error('❌ Vapi não foi inicializado');
    btnStart.disabled = true;
    btnStart.textContent = '❌ Sistema Indisponível';
    statusDescription.textContent = 'Erro ao carregar o sistema de voz';
}

// Funções auxiliares
function updateStatus(title, description, icon) {
    statusText.textContent = title;
    statusDescription.textContent = description;
    
    iconWaiting.classList.add('hidden');
    iconSpeaking.classList.add('hidden');
    iconListening.classList.add('hidden');
    
    if (icon === 'speaking') iconSpeaking.classList.remove('hidden');
    else if (icon === 'listening') iconListening.classList.remove('hidden');
    else iconWaiting.classList.remove('hidden');
}

function startTimer() {
    startTime = Date.now();
    tempoDecorrido = 0;
    
    timerInterval = setInterval(() => {
        tempoDecorrido = Math.floor((Date.now() - startTime) / 1000);
        const tempoRestante = DURACAO_MAXIMA - tempoDecorrido;
        
        if (tempoRestante <= 0) {
            // Tempo esgotado - encerrar automaticamente
            console.log('⏰ TEMPO ESGOTADO! Encerrando chamada...');
            clearInterval(timerInterval);
            
            // Feedback visual forte
            timerEl.textContent = '00:00';
            timerEl.classList.add('text-red-500', 'animate-pulse');
            timerContainer.classList.add('bg-red-500/40', 'scale-110');
            timerInfo.textContent = '⏰ TEMPO ESGOTADO!';
            timerInfo.classList.add('text-red-300', 'text-xl', 'font-bold');
            
            // Aguarda 1 segundo para o usuário ver o feedback
            setTimeout(() => {
                encerrarChamada();
            }, 1000);
            return;
        }
        
        const minutes = Math.floor(tempoRestante / 60).toString().padStart(2, '0');
        const seconds = (tempoRestante % 60).toString().padStart(2, '0');
        timerEl.textContent = `${minutes}:${seconds}`;
        
        // Remover classes anteriores
        timerEl.classList.remove('text-red-500', 'text-yellow-500', 'text-white');
        timerContainer.classList.remove('bg-red-500/30', 'bg-yellow-500/30', 'scale-110');
        timerInfo.classList.remove('text-red-300', 'text-yellow-300', 'text-xl');
        
        // Mudar cor quando estiver acabando
        if (tempoRestante <= 30) {
            // VERMELHO - Últimos 30 segundos
            timerEl.classList.add('text-red-500', 'text-6xl');
            timerContainer.classList.add('bg-red-500/30', 'scale-110');
            timerInfo.textContent = '⚠️ TEMPO ACABANDO!';
            timerInfo.classList.add('text-red-300', 'text-xl', 'font-bold', 'animate-pulse');
        } else if (tempoRestante <= 60) {
            // AMARELO - Último minuto
            timerEl.classList.add('text-yellow-400', 'text-5xl');
            timerContainer.classList.add('bg-yellow-500/30', 'scale-105');
            timerInfo.textContent = '⏰ Menos de 1 minuto';
            timerInfo.classList.add('text-yellow-300', 'text-lg', 'font-semibold');
        } else {
            // BRANCO - Normal
            timerEl.classList.add('text-white');
        }
    }, 1000);
}

function stopTimer() {
    if (timerInterval) clearInterval(timerInterval);
}

// Log de debug
console.log('🔧 Configuração:');
console.log('- Public Key:', VAPI_PUBLIC_KEY ? '✅ Configurada' : '❌ NÃO CONFIGURADA');
console.log('- Assistant ID:', ASSISTANT_ID || '❌ NÃO CONFIGURADO');
console.log('- Simulação ID:', SIMULACAO_ID);
console.log('- Duração Máxima:', DURACAO_MAXIMA, 'segundos');
