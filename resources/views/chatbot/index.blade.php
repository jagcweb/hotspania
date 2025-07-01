<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot con Documentación Personalizada</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chat-container {
            width: 100%;
            max-width: 800px;
            height: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .chat-header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .chat-header p {
            opacity: 0.9;
            font-size: 14px;
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message.user {
            align-self: flex-end;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .message.bot {
            align-self: flex-start;
            background: #f1f3f4;
            color: #333;
            border: 1px solid #e1e3e4;
        }

        .message.bot .sources {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }

        .message.bot .sources strong {
            color: #333;
        }

        .typing-indicator {
            align-self: flex-start;
            background: #f1f3f4;
            padding: 12px 16px;
            border-radius: 18px;
            display: none;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #999;
            animation: typing 1.4s infinite ease-in-out;
        }

        .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

        @keyframes typing {
            0%, 80%, 100% {
                transform: scale(0);
                opacity: 0.5;
            }
            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .input-container {
            padding: 20px;
            border-top: 1px solid #e1e3e4;
            background: white;
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .input-group input {
            flex: 1;
            padding: 12px 16px;
            border: 2px solid #e1e3e4;
            border-radius: 24px;
            outline: none;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #667eea;
        }

        .input-group button {
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 24px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .input-group button:hover:not(:disabled) {
            opacity: 0.9;
        }

        .input-group button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .error-message {
            color: #e74c3c;
            text-align: center;
            padding: 10px;
            background: #ffeaea;
            border-radius: 8px;
            margin: 10px 20px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                align-items: stretch;
            }
            
            .chat-container {
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h1>🤖 Asistente Inteligente</h1>
            <p>Pregúntame sobre la documentación disponible</p>
        </div>
        
        <div class="messages-container" id="messagesContainer">
            <div class="message bot">
                ¡Hola! Soy tu asistente especializado. Puedo ayudarte con consultas basadas en la documentación disponible. ¿En qué puedo ayudarte hoy?
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        
        <div class="input-container">
            <div class="input-group">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Escribe tu pregunta aquí..." 
                    maxlength="1000"
                >
                <button id="sendButton" onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </div>

    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const typingIndicator = document.getElementById('typingIndicator');

        function addMessage(content, isUser = false, sources = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            if (isUser) {
                messageDiv.textContent = content;
            } else {
                messageDiv.innerHTML = content.replace(/\n/g, '<br>');
                
                if (sources && sources.length > 0) {
                    const sourcesDiv = document.createElement('div');
                    sourcesDiv.className = 'sources';
                    sourcesDiv.innerHTML = `<strong>Fuentes:</strong> ${sources.join(', ')}`;
                    messageDiv.appendChild(sourcesDiv);
                }
            }
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }

        function showTyping() {
            typingIndicator.style.display = 'block';
            scrollToBottom();
        }

        function hideTyping() {
            typingIndicator.style.display = 'none';
        }

        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function setLoading(loading) {
            sendButton.disabled = loading;
            messageInput.disabled = loading;
            sendButton.textContent = loading ? 'Enviando...' : 'Enviar';
        }

        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            messagesContainer.appendChild(errorDiv);
            scrollToBottom();
            
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        async function sendMessage() {
            const message = messageInput.value.trim();
            
            if (!message) return;
            
            addMessage(message, true);
            messageInput.value = '';
            setLoading(true);
            showTyping();
            
            try {
                const response = await fetch('/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message })
                });

                const data = await response.json();
                
                hideTyping();
                
                if (data.success) {
                    addMessage(data.response, false, data.sources);
                } else {
                    showError(data.response || 'Error al procesar la respuesta');
                }
                
            } catch (error) {
                hideTyping();
                console.error('Error:', error);
                showError('Error de conexión. Por favor, inténtalo de nuevo.');
            } finally {
                setLoading(false);
                messageInput.focus();
            }
        }

        // Event listeners
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Focus en el input al cargar
        messageInput.focus();
    </script>
</body>
</html>