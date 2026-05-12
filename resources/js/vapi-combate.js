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
const statusPulse = document.getElementById('status-pulse');
const statusRing = document.getElementById('status-ring');
const voiceStatus = document.getElementById('voice-status');
const voiceSubtitle = document.getElementById('voice-subtitle');
const loadingOverlay = document.getElementById('loading-overlay');
const loadingText = document.getElementById('loading-text');
const loadingSubtitle = document.getElementById('loading-subtitle');

// Inicializar Vapi
try {
    vapi = new Vapi(VAPI_PUBLIC_KEY);
    console.log('✅ Vapi inicializado com sucesso');
} catch (error) {
    console.error('❌ Erro ao inicializar Vapi:', error);
    alert('Erro ao carregar o sistema de voz. Verifique as configurações.');
}

// Iniciar chamada
if (btnStart) {
    btnStart.addEventListener('click', async () => {
        if (!vapi) {
            alert('Sistema de voz não está disponível. Recarregue a página.');
            return;
        }
        
        // Mostrar loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
            if (loadingText) loadingText.textContent = 'Conectando...';
            if (loadingSubtitle) loadingSubtitle.textContent = 'Estabelecendo conexão com a IA';
        }
        
        try {
            console.log('🎤 Iniciando chamada com assistente:', ASSISTANT_ID);
            await vapi.start(ASSISTANT_ID);
            
            // Esconder loading
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            
            btnStart.classList.add('hidden');
            if (btnEnd) btnEnd.classList.remove('hidden');
            startTimer();
            updateStatus('Conectando...', 'Estabelecendo conexão com a IA');
            if (voiceStatus) voiceStatus.textContent = 'Conectando...';
            if (voiceSubtitle) voiceSubtitle.textContent = 'Aguarde enquanto estabelecemos a conexão';
        } catch (error) {
            console.error('❌ Erro ao iniciar:', error);
            // Esconder loading em caso de erro
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
            alert('Erro ao iniciar a chamada: ' + error.message + '\n\nVerifique:\n1. Chave pública da Vapi no .env\n2. ID do assistente configurado\n3. Permissão do microfone');
        }
    });
}

// Encerrar chamada
if (btnEnd) {
    btnEnd.addEventListener('click', () => {
        // Mostrar loading overlay
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
            if (loadingText) loadingText.textContent = 'Encerrando...';
            if (loadingSubtitle) loadingSubtitle.textContent = 'Processando análise do desempenho';
        }
        
        encerrarChamada();
    });
}

function encerrarChamada() {
    if (!vapi) {
        alert('Sistema de voz não está disponível.');
        return;
    }
    
    try {
        vapi.stop();
        stopTimer();
        updateStatus('Encerrando...', 'Processando análise do desempenho');
        if (voiceStatus) voiceStatus.textContent = 'Processando...';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Aguarde enquanto analisamos seu desempenho';
        
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
    
    // Atualizar loading para mostrar que está processando
    if (loadingOverlay && !loadingOverlay.classList.contains('hidden')) {
        if (loadingText) loadingText.textContent = 'Processando Análise...';
        if (loadingSubtitle) loadingSubtitle.textContent = 'A IA está avaliando seu desempenho';
    }
    
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
                    updateStatus('✅ Análise Concluída!', 'Redirecionando para o resultado...');
                    if (voiceStatus) voiceStatus.textContent = '✅ Análise Concluída!';
                    if (voiceSubtitle) voiceSubtitle.textContent = 'Redirecionando...';
                    
                    // Atualizar loading
                    if (loadingOverlay && !loadingOverlay.classList.contains('hidden')) {
                        if (loadingText) loadingText.textContent = '✅ Análise Concluída!';
                        if (loadingSubtitle) loadingSubtitle.textContent = 'Redirecionando para o resultado...';
                    }
                    
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
        updateStatus('Chamada Ativa', 'Você está falando com a IA');
        if (transcriptEl) transcriptEl.innerHTML = '';
        if (voiceStatus) voiceStatus.textContent = 'Chamada Ativa';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Fale naturalmente no microfone';
        
        // Status indicator verde
        if (statusPulse) {
            statusPulse.classList.remove('bg-gray-600', 'bg-yellow-500');
            statusPulse.classList.add('bg-green-500');
        }
        if (statusRing) {
            statusRing.classList.remove('bg-gray-600', 'bg-yellow-500', 'opacity-0');
            statusRing.classList.add('bg-green-500', 'opacity-75');
        }
    });
    
    vapi.on('speech-start', () => {
        console.log('🤖 IA começou a falar');
        updateStatus('IA Falando', 'Ouça atentamente a resposta');
        if (voiceStatus) voiceStatus.textContent = '🤖 IA Falando';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Ouça atentamente';
        
        // Status indicator azul
        if (statusPulse) {
            statusPulse.classList.remove('bg-gray-600', 'bg-green-500');
            statusPulse.classList.add('bg-blue-500');
        }
        if (statusRing) {
            statusRing.classList.remove('bg-gray-600', 'bg-green-500');
            statusRing.classList.add('bg-blue-500');
        }
    });
    
    vapi.on('speech-end', () => {
        console.log('🎤 IA parou de falar');
        updateStatus('Sua Vez', 'Fale agora no microfone');
        if (voiceStatus) voiceStatus.textContent = '🎤 Sua Vez';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Fale agora';
        
        // Status indicator verde
        if (statusPulse) {
            statusPulse.classList.remove('bg-gray-600', 'bg-blue-500');
            statusPulse.classList.add('bg-green-500');
        }
        if (statusRing) {
            statusRing.classList.remove('bg-gray-600', 'bg-blue-500');
            statusRing.classList.add('bg-green-500');
        }
    });
    
    vapi.on('message', (message) => {
        console.log('💬 Mensagem recebida:', message);
        if (message.type === 'transcript' && message.transcript && transcriptEl) {
            const role = message.role === 'user' ? 'Você' : 'Cliente';
            const color = message.role === 'user' ? 'text-blue-300' : 'text-green-300';
            transcriptEl.innerHTML += `<p><span class="${color} font-bold">${role}:</span> ${message.transcript}</p>`;
            transcriptEl.scrollTop = transcriptEl.scrollHeight;
        }
    });
    
    vapi.on('call-end', () => {
        console.log('📴 Chamada encerrada');
        updateStatus('Chamada Encerrada', 'Aguarde o resultado...');
        stopTimer();
        if (voiceStatus) voiceStatus.textContent = 'Chamada Encerrada';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Processando análise...';
        
        // Status indicator cinza
        if (statusPulse) {
            statusPulse.classList.remove('bg-green-500', 'bg-blue-500');
            statusPulse.classList.add('bg-gray-600');
        }
        if (statusRing) {
            statusRing.classList.remove('bg-green-500', 'bg-blue-500');
            statusRing.classList.add('bg-gray-600', 'opacity-0');
        }
    });
    
    vapi.on('error', (error) => {
        console.error('❌ Erro Vapi:', error);
        updateStatus('Erro', 'Ocorreu um problema na chamada');
        if (voiceStatus) voiceStatus.textContent = '❌ Erro';
        if (voiceSubtitle) voiceSubtitle.textContent = 'Ocorreu um problema';
        alert('Erro na chamada: ' + (error.message || 'Erro desconhecido'));
    });
} else {
    console.error('❌ Vapi não foi inicializado');
    if (btnStart) {
        btnStart.disabled = true;
        btnStart.textContent = '❌ Sistema Indisponível';
    }
    if (statusDescription) statusDescription.textContent = 'Erro ao carregar o sistema de voz';
}

// Funções auxiliares
function updateStatus(title, description) {
    if (statusText) statusText.textContent = title;
    if (statusDescription) statusDescription.textContent = description;
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
            if (timerEl) {
                timerEl.textContent = '00:00';
                timerEl.classList.add('text-red-500', 'animate-pulse');
            }
            if (timerContainer) {
                timerContainer.classList.add('scale-110');
            }
            if (timerInfo) {
                timerInfo.textContent = '⏰ TEMPO ESGOTADO!';
                timerInfo.classList.add('text-red-300', 'text-xl', 'font-bold');
            }
            
            // Mostrar loading overlay
            if (loadingOverlay) {
                loadingOverlay.classList.remove('hidden');
                if (loadingText) loadingText.textContent = 'Tempo Esgotado!';
                if (loadingSubtitle) loadingSubtitle.textContent = 'Encerrando e processando análise...';
            }
            
            // Aguarda 1 segundo para o usuário ver o feedback
            setTimeout(() => {
                encerrarChamada();
            }, 1000);
            return;
        }
        
        const minutes = Math.floor(tempoRestante / 60).toString().padStart(2, '0');
        const seconds = (tempoRestante % 60).toString().padStart(2, '0');
        if (timerEl) timerEl.textContent = `${minutes}:${seconds}`;
        
        // Remover classes anteriores
        if (timerEl) {
            timerEl.classList.remove('text-red-500', 'text-yellow-500', 'text-white');
        }
        if (timerContainer) {
            timerContainer.classList.remove('bg-red-500/30', 'bg-yellow-500/30', 'scale-110', 'scale-125', 'animate-pulse');
        }
        if (timerInfo) {
            timerInfo.classList.remove('text-red-300', 'text-yellow-300', 'text-xl', 'text-2xl', 'font-bold', 'font-black', 'animate-pulse');
        }
        
        // Mudar cor quando estiver acabando
        if (tempoRestante <= 10) {
            // VERMELHO PISCANDO - Últimos 10 segundos
            if (timerEl) {
                timerEl.classList.add('text-red-500', 'text-7xl', 'animate-pulse');
            }
            if (timerContainer) {
                timerContainer.classList.add('scale-125', 'animate-pulse');
            }
            if (timerInfo) {
                timerInfo.textContent = '🚨 TEMPO ESGOTANDO!';
                timerInfo.classList.add('text-red-300', 'text-2xl', 'font-black', 'animate-pulse');
            }
        } else if (tempoRestante <= 30) {
            // VERMELHO - Últimos 30 segundos
            if (timerEl) timerEl.classList.add('text-red-500', 'text-6xl');
            if (timerContainer) timerContainer.classList.add('scale-110');
            if (timerInfo) {
                timerInfo.textContent = '⚠️ TEMPO ACABANDO!';
                timerInfo.classList.add('text-red-300', 'text-xl', 'font-bold');
            }
        } else if (tempoRestante <= 60) {
            // AMARELO - Último minuto
            if (timerEl) timerEl.classList.add('text-yellow-400', 'text-5xl');
            if (timerContainer) timerContainer.classList.add('scale-105');
            if (timerInfo) {
                timerInfo.textContent = '⏰ Menos de 1 minuto';
                timerInfo.classList.add('text-yellow-300', 'text-lg', 'font-semibold');
            }
        } else {
            // BRANCO - Normal
            if (timerEl) timerEl.classList.add('text-white');
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
