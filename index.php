<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Registro Financiero</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .user-info {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 30px;
            background: #f8f9fa;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .ingresos { color: #4CAF50; }
        .egresos { color: #f44336; }
        .balance { color: #2196F3; }

        .controls {
            padding: 30px;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
            background: #e0e0e0;
        }

        .tab {
            flex: 1;
            padding: 15px;
            background: #e0e0e0;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .tab.active {
            background: #4CAF50;
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
        }

        .btn-voice {
            background: #2196F3;
            color: white;
        }

        .btn-voice:hover {
            background: #1976D2;
        }

        .btn-voice.recording {
            background: #f44336;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .voice-section {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .voice-feedback {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-style: italic;
        }

        .voice-controls {
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .btn-help {
            background: #FF9800;
            color: white;
        }

        .btn-help:hover {
            background: #F57C00;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.3s ease;
        }

        .modal-header {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            position: relative;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
        }

        .close:hover {
            background: rgba(255,255,255,0.2);
        }

        .modal-body {
            padding: 30px;
        }

        .tip-section {
            margin-bottom: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #FF9800;
        }

        .tip-section h3 {
            margin-top: 0;
            color: #333;
            font-size: 1.2em;
        }

        .tip-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }

        .tip-section li {
            margin: 8px 0;
            line-height: 1.4;
        }

        .platform-specific {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .platform-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .platform-card h4 {
            margin-top: 0;
            color: #FF9800;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .transactions {
            padding: 30px;
            background: #fafafa;
        }

        .transaction-item {
            background: white;
            margin-bottom: 15px;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .transaction-item.ingreso {
            border-left-color: #4CAF50;
        }

        .transaction-item.egreso {
            border-left-color: #f44336;
        }

        .transaction-info h3 {
            margin-bottom: 5px;
            color: #333;
        }

        .transaction-info p {
            color: #666;
            font-size: 14px;
        }

        .transaction-amount {
            font-size: 1.5em;
            font-weight: bold;
        }
        
        .transaction-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.delete-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: #f44336;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s ease;
    opacity: 0.7;
    z-index: 10;
}

.delete-btn:hover {
    opacity: 1;
    background: #d32f2f;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
}

.delete-btn:active {
    transform: scale(0.95);
}

.delete-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Animación de eliminación */
.transaction-item.deleting {
    animation: slideOutLeft 0.3s ease-in-out;
}

@keyframes slideOutLeft {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(-100%);
        opacity: 0;
    }
}

/* Modal de confirmación de eliminación */
.delete-modal {
    animation: fadeInModal 0.3s ease;
}

.delete-modal > div {
    animation: slideInModal 0.3s ease;
}

@keyframes fadeInModal {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideInModal {
    from { 
        transform: translateY(-50px); 
        opacity: 0; 
    }
    to { 
        transform: translateY(0); 
        opacity: 1; 
    }
}

/* Estilos responsivos para móviles */
@media (max-width: 768px) {
    .delete-btn {
        width: 36px;
        height: 36px;
        font-size: 16px;
        top: 8px;
        right: 8px;
    }
    
    .transaction-item {
        padding-right: 50px; /* Dar más espacio al botón en móviles */
    }
    
    .delete-modal > div {
        margin: 20px;
        padding: 25px;
    }
}

/* Efectos visuales mejorados */
.transaction-item.ingreso .delete-btn {
    background: #f44336;
}

.transaction-item.egreso .delete-btn {
    background: #f44336;
}

.transaction-item .delete-btn:hover {
    background: #c62828 !important;
}

/* Indicador de carga en el botón */
.delete-btn .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Mejoras para la accesibilidad */
.delete-btn:focus {
    outline: 2px solid #f44336;
    outline-offset: 2px;
}

/* Tooltip para el botón de eliminar */
.delete-btn::before {
    content: attr(title);
    position: absolute;
    bottom: 100%;
    right: 50%;
    transform: translateX(50%);
    background: #333;
    color: white;
    padding: 5px 8px;
    border-radius: 4px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: 1000;
}

.delete-btn::after {
    content: '';
    position: absolute;
    bottom: 100%;
    right: 50%;
    transform: translateX(50%);
    border: 5px solid transparent;
    border-top-color: #333;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    margin-bottom: -5px;
}

.delete-btn:hover::before,
.delete-btn:hover::after {
    opacity: 1;
}

/* Estilos para estados especiales */
.transaction-item.being-deleted {
    pointer-events: none;
    opacity: 0.6;
}

.transaction-item.being-deleted .delete-btn {
    background: #9e9e9e;
    cursor: not-allowed;
}

.btn-admin {
    background: rgba(255,193,7,0.2);
    color: white;
    border: 1px solid rgba(255,193,7,0.3);
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 12px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.btn-admin:hover {
    background: rgba(255,193,7,0.3);
    transform: translateY(-1px);
}

/* Mejorar la visibilidad en dispositivos táctiles */
@media (hover: none) and (pointer: coarse) {
    .delete-btn {
        opacity: 1; /* Siempre visible en dispositivos táctiles */
    }
}

        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 768px) {
            .stats {
                grid-template-columns: 1fr;
            }
            
            .tabs {
                flex-direction: column;
            }
            
            .transaction-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <br>
            <br>
            <h1>💰 Sistema Financiero</h1>
            <p>Gestiona tus ingresos y egresos de forma inteligente</p>
            <div class="user-info">
    <span id="userName">Cargando...</span>
    <a href="user_management.php" id="adminBtn" class="btn-logout" style="display: none; margin-right: 10px;">
        ⚙️ Admin
    </a>
    <button onclick="logout()" class="btn-logout">🚪 Salir</button>
</div>
        </div>

        <div class="stats" id="stats">
            <div class="stat-card">
                <div class="stat-value ingresos" id="totalIngresos">$0</div>
                <div>Total Ingresos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value egresos" id="totalEgresos">$0</div>
                <div>Total Egresos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value balance" id="balance">$0</div>
                <div>Balance</div>
            </div>
            <div class="stat-card">
                <div class="stat-value" id="totalTransacciones">0</div>
                <div>Transacciones</div>
            </div>
        </div>

        <div class="controls">
            <div class="tabs">
                <button class="tab active" onclick="switchTab('manual')">📝 Manual</button>
                <button class="tab" onclick="switchTab('voice')">🎤 Por Voz</button>
            </div>

            <!-- Formulario Manual -->
            <div id="manual" class="tab-content active">
                <form id="manualForm">
                    <div class="form-group">
                        <label for="tipo">Tipo de Transacción</label>
                        <select id="tipo" required>
                            <option value="">Seleccionar...</option>
                            <option value="ingreso">💰 Ingreso</option>
                            <option value="egreso">💸 Egreso</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="monto">Monto ($)</label>
                        <input type="number" id="monto" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="descripcion">Descripción (opcional)</label>
                        <textarea id="descripcion" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Crear Transacción</button>
                </form>
            </div>

            <!-- Control por Voz -->
            <div id="voice" class="tab-content">
                <div class="voice-section">
                    <h3>🎤 Control por Voz</h3>
                    
                    <!-- Verificación de HTTPS -->
                    <div id="httpsWarning" style="display: none; background: #fff3cd; color: #856404; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        ⚠️ <strong>Atención:</strong> Para usar el micrófono, la página debe ejecutarse en HTTPS. 
                        <br>Si estás en desarrollo local, usa: <code>localhost</code> en lugar de <code>127.0.0.1</code>
                    </div>
                    
                    <p><strong>Ejemplos de comandos:</strong></p>
                    <ul style="text-align: left; display: inline-block; margin: 10px 0;">
                        <li>"Crear nuevo ingreso de 250000 pesos con título ganancia"</li>
                        <li>"Nuevo egreso de 50000 pesos título compras"</li>
                        <li>"Ingreso de 15000 con título freelance"</li>
                    </ul>
                    
                    <div class="voice-controls">
                        <button id="voiceBtn" class="btn btn-voice" onclick="toggleVoiceRecording()">
                            🎤 Iniciar Grabación
                        </button>
                        
                        <button class="btn btn-help" onclick="showTipsModal()">
                            💡 Consejos
                        </button>
                    </div>
                    
                    <div id="voiceFeedback" class="voice-feedback"></div>
                </div>
            </div>

            <div id="alerts"></div>
        </div>

        <div class="transactions">
            <h2>📊 Transacciones Recientes</h2>
            <div id="transactionsList"></div>
        </div>
    </div>

    <!-- Modal de Consejos -->
    <div id="tipsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>💡 Consejos para Reconocimiento de Voz</h2>
                <button class="close" onclick="closeTipsModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="tip-section">
                    <h3>📱 Problemas en Móviles</h3>
                    <ul>
                        <li><strong>Habla más fuerte:</strong> Los micrófonos móviles son menos sensibles</li>
                        <li><strong>Acércate al micrófono:</strong> Mantén el teléfono a 10-15cm de tu boca</li>
                        <li><strong>Ambiente silencioso:</strong> El ruido de fondo interfiere más en móviles</li>
                        <li><strong>Pausa después del beep:</strong> Espera 1 segundo antes de hablar</li>
                        <li><strong>Habla despacio y claro:</strong> Pronuncia cada palabra distintamente</li>
                        <li><strong>Usa frases cortas:</strong> Divide comandos largos en partes</li>
                    </ul>
                </div>

                <div class="tip-section">
                    <h3>🎤 Técnicas de Dictado</h3>
                    <ul>
                        <li><strong>Ritmo constante:</strong> No hables ni muy rápido ni muy lento</li>
                        <li><strong>Pronunciación clara:</strong> Articula bien las consonantes</li>
                        <li><strong>Sin muletillas:</strong> Evita "eh", "um", "este"</li>
                        <li><strong>Números en español:</strong> Di "doscientos cincuenta mil" en lugar de "250000"</li>
                        <li><strong>Signos de puntuación:</strong> Di "coma" o "punto" si es necesario</li>
                    </ul>
                </div>

                <div class="tip-section">
                    <h3>✅ Comandos Recomendados</h3>
                    <ul>
                        <li><strong>Formato simple:</strong> "Ingreso de quince mil título salario"</li>
                        <li><strong>Sin artículos extra:</strong> Evita "un", "el", "la" innecesarios</li>
                        <li><strong>Palabras clave claras:</strong> "Ingreso", "Egreso", "título"</li>
                        <li><strong>Números en palabras:</strong> "Cinco mil" mejor que "5000"</li>
                    </ul>
                </div>

                <div class="platform-specific">
                    <div class="platform-card">
                        <h4>📱 Android</h4>
                        <ul>
                            <li>Usa Chrome o Firefox</li>
                            <li>Habilita permisos en Configuración > Apps</li>
                            <li>Verifica que el Asistente de Google no interfiera</li>
                            <li>Desactiva "Ok Google" temporalmente</li>
                        </ul>
                    </div>

                    <div class="platform-card">
                        <h4>🍎 iOS/iPhone</h4>
                        <ul>
                            <li>Usa Safari (mejor compatibilidad)</li>
                            <li>Ve a Ajustes > Safari > Cámara y Micrófono</li>
                            <li>Desactiva Siri temporalmente</li>
                            <li>Habla directamente al micrófono inferior</li>
                        </ul>
                    </div>
                </div>

                <div class="tip-section">
                    <h3>🔧 Solución de Problemas</h3>
                    <ul>
                        <li><strong>Se detiene automáticamente:</strong> El navegador no detecta voz - habla más fuerte</li>
                        <li><strong>No reconoce palabras:</strong> Prueba con números en texto ("mil" en lugar de "1000")</li>
                        <li><strong>Error "not-allowed":</strong> Otorga permisos de micrófono en el navegador</li>
                        <li><strong>No funciona en HTTPS:</strong> Usa la versión localhost o configura SSL</li>
                        <li><strong>Cortes de audio:</strong> Cierra otras apps que usen el micrófono</li>
                    </ul>
                </div>

                <div class="tip-section">
                    <h3>🎯 Ejemplos Perfectos</h3>
                    <ul>
                        <li>"Crear ingreso de cincuenta mil título freelance"</li>
                        <li>"Nuevo egreso de diez mil título comida"</li>
                        <li>"Ingreso de doscientos mil con título salario"</li>
                        <li>"Egreso de cinco mil título transporte"</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
let isRecording = false;
let recognition = null;
let audioContext = null;
let mediaStream = null;

// Detectar dispositivo móvil de forma más precisa
function detectMobile() {
    const userAgent = navigator.userAgent.toLowerCase();
    const isMobile = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent);
    const isTablet = /ipad|android(?=.*mobile)/i.test(userAgent);
    const touchDevice = 'ontouchstart' in window;
    
    return {
        mobile: isMobile,
        tablet: isTablet,
        touch: touchDevice,
        ios: /ipad|iphone|ipod/.test(userAgent),
        android: /android/.test(userAgent),
        chrome: /chrome/.test(userAgent),
        safari: /safari/.test(userAgent) && !/chrome/.test(userAgent)
    };
}

// Configuración específica por dispositivo
function getDeviceConfig() {
    const device = detectMobile();
    
    if (device.ios) {
        return {
            continuous: false,
            interimResults: false,
            maxAlternatives: 5,
            speechTimeout: 8000,
            endTimeout: 4000,
            noSpeechTimeout: 6000,
            recommendedBrowser: 'Safari',
            tips: [
                'Usa Safari para mejor compatibilidad',
                'Habla directamente al micrófono inferior',
                'Desactiva Siri temporalmente',
                'Mantén el teléfono cerca de tu boca (10-15cm)'
            ]
        };
    } else if (device.android) {
        return {
            continuous: false,
            interimResults: false,
            maxAlternatives: 3,
            speechTimeout: 10000,
            endTimeout: 5000,
            noSpeechTimeout: 8000,
            recommendedBrowser: 'Chrome',
            tips: [
                'Usa Chrome o Firefox',
                'Habla MÁS FUERTE que en PC',
                'Desactiva "Ok Google" temporalmente',
                'Verifica permisos en Configuración > Apps'
            ]
        };
    } else {
        return {
            continuous: false,
            interimResults: false,
            maxAlternatives: 1,
            speechTimeout: 5000,
            endTimeout: 2000,
            noSpeechTimeout: 5000,
            recommendedBrowser: 'Chrome/Firefox/Edge',
            tips: [
                'Habla claro y pausado',
                'Asegúrate de tener permisos de micrófono'
            ]
        };
    }
}

// Función para validar respuesta JSON
function isValidJSON(text) {
    try {
        JSON.parse(text);
        return true;
    } catch (e) {
        return false;
    }
}

// Función para manejar respuestas del servidor
async function handleServerResponse(response) {
    if (response.status === 401) {
        console.warn('Sesión expirada - redirigiendo al login');
        alert('Tu sesión ha expirado. Redirigiendo al login...');
        window.location.href = 'login.php';
        throw new Error('SESION_EXPIRADA');
    }
    
    if (!response.ok) {
        throw new Error(`HTTP Error: ${response.status} ${response.statusText}`);
    }
    
    const text = await response.text();
    
    // Verificar si la respuesta es HTML (página de login)
    if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
        console.error('Servidor devolvió HTML en lugar de JSON:', text.substring(0, 200) + '...');
        alert('Sesión no válida. Redirigiendo al login...');
        window.location.href = 'login.php';
        throw new Error('RESPUESTA_HTML_INVALIDA');
    }
    
    if (!isValidJSON(text)) {
        console.error('Respuesta no es JSON válido:', text);
        throw new Error('El servidor devolvió una respuesta inválida');
    }
    
    return JSON.parse(text);
}

// Inicializar reconocimiento de voz mejorado
function initializeVoiceRecognition() {
    const device = detectMobile();
    const config = getDeviceConfig();
    
    // Mostrar tips específicos del dispositivo
    if (device.mobile || device.tablet) {
        showMobileTips(device, config);
    }
    
    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        
        // Configuración básica
        recognition.lang = 'es-ES';
        recognition.continuous = config.continuous;
        recognition.interimResults = config.interimResults;
        recognition.maxAlternatives = config.maxAlternatives;
        
        // Configuración específica para móviles
        if (device.mobile || device.tablet) {
            recognition.serviceURI = undefined;
        }
        
        recognition.onstart = function() {
            isRecording = true;
            updateVoiceButton(true);
            
            if (device.mobile) {
                showVoiceFeedback('📱 Escuchando... Habla FUERTE y CLARO', 'info');
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
            } else {
                showVoiceFeedback('🎤 Escuchando...', 'info');
            }
        };
        
        recognition.onresult = function(event) {
            handleVoiceResult(event, device, config);
        };
        
        recognition.onerror = function(event) {
            handleVoiceError(event, device, config);
        };
        
        recognition.onend = function() {
            resetVoiceButton();
            if (device.mobile && navigator.vibrate) {
                navigator.vibrate([50, 50, 50]);
            }
        };
        
        return true;
    } else {
        const voiceBtn = document.getElementById('voiceBtn');
        if (voiceBtn) {
            voiceBtn.textContent = '❌ Navegador no compatible';
            voiceBtn.disabled = true;
        }
        return false;
    }
}

// Mostrar tips específicos para móviles
function showMobileTips(device, config) {
    const tipsContainer = document.getElementById('voiceFeedback');
    if (!tipsContainer) return;
    
    let deviceName = 'Móvil';
    if (device.ios) deviceName = 'iOS/iPhone';
    else if (device.android) deviceName = 'Android';
    
    const tipsHTML = `
        <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 10px 0; border: 1px solid #ffeaa7;">
            <h4 style="margin: 0 0 10px 0; color: #856404;">📱 Consejos para ${deviceName}</h4>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px;">
                ${config.tips.map(tip => `<li style="margin: 5px 0;">${tip}</li>`).join('')}
            </ul>
            <p style="margin: 10px 0 0 0; font-size: 12px; color: #856404;">
                <strong>Navegador recomendado:</strong> ${config.recommendedBrowser}
            </p>
        </div>
    `;
    
    tipsContainer.innerHTML = tipsHTML;
}

// Manejar resultados de voz mejorado
function handleVoiceResult(event, device, config) {
    let transcript = '';
    let confidence = 0;
    let allResults = [];
    
    if (event.results.length > 0) {
        const result = event.results[0];
        
        for (let i = 0; i < result.length; i++) {
            allResults.push({
                transcript: result[i].transcript,
                confidence: result[i].confidence || 0.5
            });
        }
        
        if (device.mobile || device.tablet) {
            const bestResult = allResults.reduce((best, current) => {
                return current.confidence > best.confidence ? current : best;
            }, allResults[0]);
            
            transcript = bestResult.transcript;
            confidence = bestResult.confidence;
            
            if (confidence < 0.5 && allResults.length > 1) {
                const keywordResult = allResults.find(r => 
                    /ingreso|egreso|crear|nuevo/i.test(r.transcript)
                );
                
                if (keywordResult && keywordResult.confidence > 0.3) {
                    transcript = keywordResult.transcript;
                    confidence = keywordResult.confidence;
                }
            }
        } else {
            transcript = allResults[0].transcript;
            confidence = allResults[0].confidence || 0.5;
        }
    }
    
    console.log('Resultados de voz:', allResults);
    
    if (transcript) {
        const confidencePercent = Math.round(confidence * 100);
        let message = `Procesando: "${transcript}"`;
        
        if (device.mobile) {
            message += ` (${confidencePercent}% confianza)`;
            
            if (confidence < 0.7 && allResults.length > 1) {
                message += `\n\nAlternativas detectadas:`;
                allResults.slice(0, 3).forEach((alt, i) => {
                    message += `\n${i + 1}. "${alt.transcript}" (${Math.round((alt.confidence || 0.5) * 100)}%)`;
                });
            }
        }
        
        showVoiceFeedback(message, 'info');
        
        if (device.mobile && navigator.vibrate) {
            navigator.vibrate(200);
        }
        
        processVoiceCommand(transcript);
    } else {
        let errorMsg = '❌ No se pudo capturar el audio.';
        
        if (device.mobile) {
            errorMsg += ' En móviles: habla MÁS FUERTE y acércate al micrófono.';
        } else {
            errorMsg += ' Intenta de nuevo hablando más claro.';
        }
        
        showVoiceFeedback(errorMsg, 'error');
    }
}

// Manejar errores de voz mejorado
function handleVoiceError(event, device, config) {
    let errorMessage = '';
    
    switch(event.error) {
        case 'not-allowed':
            errorMessage = '❌ Permisos de micrófono denegados. ';
            if (device.ios) {
                errorMessage += 'Ve a Ajustes > Safari > Cámara y Micrófono.';
            } else if (device.android) {
                errorMessage += 'Ve a Configuración > Apps > Chrome > Permisos.';
            } else {
                errorMessage += 'Haz clic en el ícono del micrófono en la barra de direcciones.';
            }
            break;
            
        case 'no-speech':
            if (device.mobile) {
                errorMessage = '📱 No se detectó voz. En móviles: habla MÁS FUERTE (como si gritaras) y acércate al micrófono.';
            } else {
                errorMessage = '🔇 No se detectó voz. Intenta hablar más fuerte o acércate al micrófono.';
            }
            break;
            
        case 'audio-capture':
            errorMessage = '🎤 Error de captura de audio. ';
            if (device.mobile) {
                errorMessage += 'Verifica que no haya otras apps usando el micrófono.';
            } else {
                errorMessage += 'Verifica que el micrófono esté conectado y funcionando.';
            }
            break;
            
        case 'network':
            errorMessage = '🌐 Error de red. ';
            if (device.mobile) {
                errorMessage += 'Verifica tu conexión WiFi o datos móviles.';
            } else {
                errorMessage += 'Verifica tu conexión a internet.';
            }
            break;
            
        case 'service-not-allowed':
            errorMessage = '🚫 Servicio no permitido. ';
            if (window.location.protocol !== 'https:') {
                errorMessage += 'El reconocimiento de voz requiere HTTPS.';
            } else {
                errorMessage += 'El navegador bloqueó el servicio de reconocimiento de voz.';
            }
            break;
            
        case 'aborted':
            errorMessage = '⏹️ Grabación detenida.';
            break;
            
        default:
            errorMessage = `❌ Error: ${event.error}`;
            if (device.mobile) {
                errorMessage += ' - Intenta reiniciar el navegador.';
            }
    }
    
    showVoiceFeedback(errorMessage, 'error');
    resetVoiceButton();
}

// Solicitar permisos de micrófono de forma más robusta
async function requestMicrophonePermission() {
    const device = detectMobile();
    
    try {
        if (navigator.permissions) {
            try {
                const permissionStatus = await navigator.permissions.query({ name: 'microphone' });
                
                if (permissionStatus.state === 'denied') {
                    throw new Error('PERMISSION_DENIED');
                }
                
                if (permissionStatus.state === 'granted') {
                    return true;
                }
            } catch (permError) {
                console.log('No se pudo verificar permisos (normal en algunos navegadores)');
            }
        }
        
        const stream = await navigator.mediaDevices.getUserMedia({ 
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true,
                ...(device.mobile && {
                    sampleRate: 16000,
                    channelCount: 1
                })
            }
        });
        
        mediaStream = stream;
        stream.getTracks().forEach(track => track.stop());
        
        return true;
        
    } catch (error) {
        let errorMessage = '';
        
        if (error.message === 'PERMISSION_DENIED' || error.name === 'NotAllowedError') {
            if (device.ios) {
                errorMessage = '❌ Permisos denegados. Ve a Ajustes > Safari > Cámara y Micrófono y permite el acceso.';
            } else if (device.android) {
                errorMessage = '❌ Permisos denegados. Ve a Configuración > Apps > ' + (device.chrome ? 'Chrome' : 'Navegador') + ' > Permisos y habilita el micrófono.';
            } else {
                errorMessage = '❌ Permisos denegados. Haz clic en el ícono del micrófono en la barra de direcciones y permite el acceso.';
            }
        } else if (error.name === 'NotFoundError') {
            errorMessage = '🎤 No se encontró micrófono. Verifica que esté conectado.';
        } else if (error.name === 'NotSupportedError') {
            errorMessage = '🚫 Tu navegador no soporta el acceso al micrófono.';
        } else {
            errorMessage = '❌ Error al acceder al micrófono: ' + error.message;
        }
        
        showVoiceFeedback(errorMessage, 'error');
        return false;
    }
}

// Toggle grabación de voz mejorado
async function toggleVoiceRecording() {
    if (!recognition) {
        showVoiceFeedback('❌ Reconocimiento de voz no disponible', 'error');
        return;
    }
    
    if (isRecording) {
        recognition.stop();
        return;
    }
    
    const hasPermission = await requestMicrophonePermission();
    if (!hasPermission) {
        return;
    }
    
    try {
        recognition.start();
    } catch (error) {
        showVoiceFeedback('❌ Error al iniciar reconocimiento: ' + error.message, 'error');
        resetVoiceButton();
    }
}

// Actualizar botón de voz
function updateVoiceButton(recording) {
    const btn = document.getElementById('voiceBtn');
    if (!btn) return;
    
    if (recording) {
        btn.classList.add('recording');
        btn.textContent = '🛑 Detener Grabación';
    } else {
        btn.classList.remove('recording');
        btn.textContent = '🎤 Iniciar Grabación';
    }
}

// Resetear botón de voz
function resetVoiceButton() {
    isRecording = false;
    updateVoiceButton(false);
}

// Función para cambiar entre pestañas
function switchTab(tabName) {
    // Remover clase active de todas las pestañas y contenidos
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
    
    // Agregar clase active a la pestaña clickeada
    if (event && event.target) {
        event.target.classList.add('active');
    }
    
    // Mostrar el contenido correspondiente
    const tabContent = document.getElementById(tabName);
    if (tabContent) {
        tabContent.classList.add('active');
    }
}

// Función para logout
function logout() {
    if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
        window.location.href = 'auth.php?action=logout';
    }
}

// Función para mostrar modal de consejos
function showTipsModal() {
    const modal = document.getElementById('tipsModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

// Función para cerrar modal de consejos
function closeTipsModal() {
    const modal = document.getElementById('tipsModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Función para mostrar transacciones con botones de eliminar
function displayTransactions(transactions) {
    const container = document.getElementById('transactionsList');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (!transactions || transactions.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; color: #666; padding: 40px;">
                <div style="font-size: 3em; color: #ddd; margin-bottom: 20px;">📊</div>
                <p>No hay transacciones registradas</p>
                <p><small>Crea tu primera transacción usando el formulario manual o por voz</small></p>
            </div>
        `;
        return;
    }
    
    transactions.forEach(transaction => {
        const div = document.createElement('div');
        div.className = `transaction-item ${transaction.tipo}`;
        div.style.position = 'relative';
        
        const fecha = new Date(transaction.fecha_creacion).toLocaleString('es-AR');
        const metodoBadge = transaction.metodo_creacion === 'audio' ? '🎤' : '📝';
        
        div.innerHTML = `
            <div class="transaction-info">
                <h3>${escapeHtml(transaction.titulo)} ${metodoBadge}</h3>
                <p>${escapeHtml(transaction.descripcion || 'Sin descripción')}</p>
                <p><small>📅 ${fecha}</small></p>
            </div>
            <div class="transaction-amount ${transaction.tipo}">
                ${transaction.tipo === 'ingreso' ? '+' : '-'}${formatCurrency(transaction.monto)}
            </div>
            <button class="delete-btn" onclick="eliminarTransaccion(${transaction.id}, '${escapeHtml(transaction.titulo).replace(/'/g, "\\'")}', ${transaction.monto})" title="Eliminar transacción">
                ✕
            </button>
        `;
        
        container.appendChild(div);
    });
}

// Función para escapar HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Función para eliminar transacción
async function eliminarTransaccion(id, titulo, monto) {
    const confirmMessage = `¿Estás seguro de que quieres eliminar esta transacción?\n\n"${titulo}"\nMonto: ${formatCurrency(monto)}`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    try {
        const deleteBtn = document.querySelector(`button[onclick*="eliminarTransaccion(${id}"]`);
        if (deleteBtn) {
            deleteBtn.innerHTML = '⏳';
            deleteBtn.disabled = true;
        }
        
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: 'eliminar_transaccion',
                id: id
            })
        });
        
        const result = await handleServerResponse(response);
        
        if (result.success) {
            showAlert('✅ ' + result.message, 'success');
            
            const transactionElement = deleteBtn.closest('.transaction-item');
            if (transactionElement) {
                transactionElement.style.transition = 'all 0.3s ease';
                transactionElement.style.transform = 'translateX(-100%)';
                transactionElement.style.opacity = '0';
                
                setTimeout(() => {
                    transactionElement.remove();
                }, 300);
            }
            
            setTimeout(() => {
                loadData();
            }, 500);
            
            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }
            
        } else {
            throw new Error(result.message || 'Error al eliminar la transacción');
        }
        
    } catch (error) {
        console.error('Error al eliminar transacción:', error);
        
        if (error.message !== 'SESION_EXPIRADA' && error.message !== 'RESPUESTA_HTML_INVALIDA') {
            showAlert('❌ Error al eliminar la transacción: ' + error.message, 'error');
            
            const deleteBtn = document.querySelector(`button[onclick*="eliminarTransaccion(${id}"]`);
            if (deleteBtn) {
                deleteBtn.innerHTML = '✕';
                deleteBtn.disabled = false;
            }
        }
    }
}

// Función para mostrar feedback de voz
function showVoiceFeedback(message, type = 'info') {
    const feedback = document.getElementById('voiceFeedback');
    if (!feedback) return;
    
    feedback.textContent = message;
    feedback.className = 'voice-feedback';
    if (type === 'error') feedback.style.color = '#f44336';
    else if (type === 'success') feedback.style.color = '#4CAF50';
    else feedback.style.color = '#2196F3';
}

// Función para procesar comando de voz
async function processVoiceCommand(text) {
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                action: 'procesar_voz',
                texto: text
            })
        });
        
        const result = await handleServerResponse(response);
        
        if (result.success) {
            showAlert('✅ Transacción creada exitosamente por voz!', 'success');
            showVoiceFeedback('✅ Transacción creada exitosamente', 'success');
            loadData();
        } else {
            showAlert('❌ ' + result.message, 'error');
            showVoiceFeedback('❌ ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error al procesar comando de voz:', error);
        
        if (error.message !== 'SESION_EXPIRADA' && error.message !== 'RESPUESTA_HTML_INVALIDA') {
            showAlert('❌ Error al procesar comando de voz', 'error');
            showVoiceFeedback('❌ Error de conexión', 'error');
        }
    }
}

// Función showAlert
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    
    const alertsContainer = document.getElementById('alerts');
    if (alertsContainer) {
        alertsContainer.appendChild(alertDiv);
        
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    } else {
        alert(message);
    }
}

// Función para verificar sesión
async function verificarSesion() {
    try {
        const response = await fetch('auth.php?action=check', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }
        
        const text = await response.text();
        
        if (!isValidJSON(text)) {
            console.error('Respuesta de auth.php no es JSON válido:', text);
            alert('Error de autenticación. Redirigiendo al login...');
            window.location.href = 'login.php';
            return;
        }
        
        const data = JSON.parse(text);
        
        if (!data.authenticated) {
            alert('Tu sesión ha expirado. Redirigiendo al login...');
            window.location.href = 'login.php';
        } else if (data.user) {
            const userNameElement = document.getElementById('userName');
            const adminBtn = document.getElementById('adminBtn');
            
            if (userNameElement) {
                userNameElement.textContent = `👤 ${data.user.nombre} (${data.user.username})`;
            }
            
            // Mostrar botón de admin solo si es administrador
            if (adminBtn && data.is_admin) {
                adminBtn.style.display = 'inline-block';
            }
        }
    } catch (error) {
        console.error('Error verificando sesión:', error);
        // No redirigir automáticamente en caso de error de red
        // showAlert('Error de conexión al verificar sesión', 'error');
    }
}

// Función para cargar datos
async function loadData() {
    try {
        // Cargar resumen
        const resumenResponse = await fetch('api.php?action=obtener_resumen', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const resumen = await handleServerResponse(resumenResponse);
        
        if (resumen.success) {
            const totalIngresosEl = document.getElementById('totalIngresos');
            const totalEgresosEl = document.getElementById('totalEgresos');
            const balanceEl = document.getElementById('balance');
            const totalTransaccionesEl = document.getElementById('totalTransacciones');
            
            if (totalIngresosEl) totalIngresosEl.textContent = formatCurrency(resumen.data.total_ingresos);
            if (totalEgresosEl) totalEgresosEl.textContent = formatCurrency(resumen.data.total_egresos);
            if (balanceEl) balanceEl.textContent = formatCurrency(resumen.data.balance);
            if (totalTransaccionesEl) totalTransaccionesEl.textContent = resumen.data.total_transacciones;
        }
        
        // Cargar transacciones
        const transaccionesResponse = await fetch('api.php?action=obtener_transacciones', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const transacciones = await handleServerResponse(transaccionesResponse);
        
        if (transacciones.success) {
            displayTransactions(transacciones.data);
        }
        
    } catch (error) {
        console.error('Error al cargar datos:', error);
        
        if (error.message !== 'SESION_EXPIRADA' && error.message !== 'RESPUESTA_HTML_INVALIDA') {
            showAlert('❌ Error de conexión. Verifica que el servidor esté funcionando.', 'error');
        }
    }
}

// Función para formatear moneda
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-AR', {
        style: 'currency',
        currency: 'ARS'
    }).format(amount || 0);
}

// Configurar todos los event listeners
function setupEventListeners() {
    const manualForm = document.getElementById('manualForm');
    if (manualForm) {
        manualForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const tipoElement = document.getElementById('tipo');
            const montoElement = document.getElementById('monto');
            const tituloElement = document.getElementById('titulo');
            const descripcionElement = document.getElementById('descripcion');
            
            if (!tipoElement || !montoElement || !tituloElement) {
                showAlert('❌ Error: Elementos del formulario no encontrados', 'error');
                return;
            }
            
            const datos = {
                tipo: tipoElement.value,
                monto: parseFloat(montoElement.value),
                titulo: tituloElement.value,
                descripcion: descripcionElement ? descripcionElement.value : '',
                metodo_creacion: 'manual'
            };
            
            // Validaciones básicas
            if (!datos.tipo || !datos.monto || !datos.titulo) {
                showAlert('❌ Por favor completa todos los campos obligatorios', 'error');
                return;
            }
            
            if (datos.monto <= 0) {
                showAlert('❌ El monto debe ser mayor a 0', 'error');
                return;
            }
            
            try {
                const response = await fetch('api.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'crear_transaccion',
                        datos: datos
                    })
                });
                
                const result = await handleServerResponse(response);
                
                if (result.success) {
                    showAlert('✅ Transacción creada exitosamente!', 'success');
                    this.reset();
                    loadData();
                } else {
                    showAlert('❌ ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error al crear transacción:', error);
                
                if (error.message !== 'SESION_EXPIRADA' && error.message !== 'RESPUESTA_HTML_INVALIDA') {
                    showAlert('❌ Error al crear transacción', 'error');
                }
            }
        });
    }
    
    // Event listener para cerrar modal con click fuera
    window.onclick = function(event) {
        const modal = document.getElementById('tipsModal');
        if (event.target === modal) {
            closeTipsModal();
        }
    }
    
    // Event listener para cerrar modal con tecla Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTipsModal();
        }
    });
}

// Función para reintentar operación
async function retryOperation(operation, maxRetries = 3, delay = 1000) {
    for (let i = 0; i < maxRetries; i++) {
        try {
            return await operation();
        } catch (error) {
            console.warn(`Intento ${i + 1} falló:`, error.message);
            
            if (error.message === 'SESION_EXPIRADA' || error.message === 'RESPUESTA_HTML_INVALIDA') {
                throw error; // No reintentar errores de autenticación
            }
            
            if (i === maxRetries - 1) {
                throw error; // Último intento
            }
            
            // Esperar antes del siguiente intento
            await new Promise(resolve => setTimeout(resolve, delay * (i + 1)));
        }
    }
}

// Función para verificar conectividad
async function checkConnectivity() {
    try {
        const response = await fetch('api.php?action=ping', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            signal: AbortSignal.timeout(5000) // Timeout de 5 segundos
        });
        
        return response.ok;
    } catch (error) {
        console.warn('Verificación de conectividad falló:', error);
        return false;
    }
}

// Función para manejar errores de red
function handleNetworkError(error) {
    if (error.name === 'TypeError' && error.message.includes('fetch')) {
        showAlert('❌ Sin conexión a internet. Verifica tu conexión.', 'error');
    } else if (error.name === 'AbortError') {
        showAlert('❌ La solicitud tardó demasiado. Intenta nuevamente.', 'error');
    } else {
        showAlert('❌ Error de red: ' + error.message, 'error');
    }
}

// Función mejorada para cargar datos con reintentos
async function loadDataWithRetry() {
    try {
        await retryOperation(async () => {
            const isConnected = await checkConnectivity();
            if (!isConnected) {
                throw new Error('Sin conexión al servidor');
            }
            
            await loadData();
        }, 3, 2000);
    } catch (error) {
        console.error('Error al cargar datos después de reintentos:', error);
        handleNetworkError(error);
    }
}

// Función para inicializar la aplicación
function initializeApp() {
    console.log('Inicializando aplicación financiera...');
    
    // Verificar elementos críticos del DOM
    const criticalElements = ['userName', 'totalIngresos', 'totalEgresos', 'balance', 'transactionsList'];
    const missingElements = criticalElements.filter(id => !document.getElementById(id));
    
    if (missingElements.length > 0) {
        console.error('Elementos críticos faltantes:', missingElements);
        showAlert('❌ Error en la interfaz. Recarga la página.', 'error');
        return false;
    }
    
    // Verificar soporte HTTPS si es necesario
    const httpsWarning = document.getElementById('httpsWarning');
    if (httpsWarning && location.protocol !== 'https:' && 
        location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
        httpsWarning.style.display = 'block';
    }
    
    // Inicializar reconocimiento de voz
    const voiceInitialized = initializeVoiceRecognition();
    if (voiceInitialized) {
        console.log('✅ Reconocimiento de voz inicializado correctamente');
    } else {
        console.warn('⚠️ Reconocimiento de voz no disponible');
    }
    
    // Configurar event listeners
    setupEventListeners();
    
    // Verificar sesión inicial
    verificarSesion();
    
    // Cargar datos iniciales
    loadDataWithRetry();
    
    // Configurar verificación periódica de sesión (cada 5 minutos)
    setInterval(() => {
        verificarSesion();
    }, 300000);
    
    // Configurar actualización periódica de datos (cada 30 segundos)
    setInterval(() => {
        loadData().catch(error => {
            console.warn('Error en actualización automática:', error);
        });
    }, 30000);
    
    console.log('✅ Aplicación inicializada correctamente');
    return true;
}

// Función de limpieza al cerrar la página
function cleanup() {
    if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
    }
    
    if (recognition) {
        recognition.stop();
    }
    
    if (audioContext) {
        audioContext.close();
    }
}

// Event listeners para el ciclo de vida de la página
window.addEventListener('load', function() {
    initializeApp();
});

window.addEventListener('beforeunload', function() {
    cleanup();
});

window.addEventListener('unload', function() {
    cleanup();
});

// Manejar errores globales
window.addEventListener('error', function(event) {
    console.error('Error global capturado:', event.error);
    showAlert('❌ Se produjo un error inesperado. Recarga la página si persiste.', 'error');
});

// Manejar promesas rechazadas no capturadas
window.addEventListener('unhandledrejection', function(event) {
    console.error('Promesa rechazada no manejada:', event.reason);
    event.preventDefault(); // Prevenir que aparezca en la consola
    
    if (event.reason && event.reason.message !== 'SESION_EXPIRADA' && 
        event.reason.message !== 'RESPUESTA_HTML_INVALIDA') {
        showAlert('❌ Error de conexión. Verifica tu conexión a internet.', 'error');
    }
});

// Detectar cambios en la conectividad
window.addEventListener('online', function() {
    showAlert('✅ Conexión restaurada', 'success');
    loadDataWithRetry();
});

window.addEventListener('offline', function() {
    showAlert('⚠️ Sin conexión a internet', 'error');
});

// Funciones globales para uso en HTML (onclick, etc.)
window.switchTab = switchTab;
window.logout = logout;
window.showTipsModal = showTipsModal;
window.closeTipsModal = closeTipsModal;
window.toggleVoiceRecording = toggleVoiceRecording;
window.eliminarTransaccion = eliminarTransaccion;
    </script>
</body>
</html>