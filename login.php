<?php
// login.php - Sistema de autenticación con base de datos
session_start();

// Si ya está logueado, redireccionar
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: index.php');
    exit;
}

require_once 'auth.php';

$mensaje = '';
$tipo_mensaje = '';
$mostrar_usuarios_demo = false;

// Procesar login
if ($_POST && isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $auth = new Auth();
        $resultado = $auth->login($username, $password);
        
        if ($resultado['success']) {
            // Login exitoso - redireccionar
            header('Location: index.php');
            exit;
        } else {
            $mensaje = $resultado['message'];
            $tipo_mensaje = 'error';
            $mostrar_usuarios_demo = true; // Mostrar ayuda después de error
        }
    } else {
        $mensaje = 'Por favor, completa todos los campos';
        $tipo_mensaje = 'error';
    }
}

// Verificar si existen usuarios en la base de datos
$database = new Database();
$conn = $database->getConnection();
$usuarios_existen = false;

if ($conn) {
    try {
        $stmt = $conn->query("SELECT COUNT(*) FROM usuarios WHERE activo = TRUE");
        $count = $stmt->fetchColumn();
        $usuarios_existen = ($count > 0);
        
        // Si no hay usuarios, mostrar mensaje especial
        if (!$usuarios_existen) {
            $mensaje = 'Sistema inicializándose... Ejecuta setup.php primero.';
            $tipo_mensaje = 'info';
        }
    } catch (Exception $e) {
        $mensaje = 'La Base de datos esta vacía. Inicia "Setup Completo".';
        $tipo_mensaje = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Financiero</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .login-header p {
            opacity: 0.9;
            font-size: 1.1em;
        }

        .login-form {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4CAF50;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .mensaje {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .mensaje.error {
            background: #fdf0f0;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .mensaje.success {
            background: #f0f8f0;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensaje.info {
            background: #e7f3ff;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .demo-info {
            background: #e7f3ff;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: none;
        }

        .demo-info.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .demo-info h4 {
            color: #0c5460;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .demo-info .credential {
            background: white;
            padding: 8px 12px;
            border-radius: 5px;
            margin: 5px 0;
            font-family: monospace;
            border: 1px solid #b8daff;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .demo-info .credential:hover {
            background: #f8f9ff;
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .security-features {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #eee;
        }

        .security-features h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .security-features ul {
            list-style: none;
            font-size: 12px;
            color: #666;
        }

        .security-features li {
            margin: 5px 0;
            padding-left: 15px;
            position: relative;
        }

        .security-features li:before {
            content: "🔒";
            position: absolute;
            left: 0;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-header h1 {
                font-size: 1.5em;
            }
        }

        .input-icon {
            position: relative;
        }

        .input-icon:before {
            content: "";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            background-size: contain;
            z-index: 1;
        }

        .input-icon.user:before {
            content: "👤";
        }

        .input-icon.password:before {
            content: "🔐";
        }

        .input-icon input {
            padding-left: 45px;
        }

        .setup-link {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            font-size: 14px;
        }

        .setup-link a {
            color: #856404;
            text-decoration: none;
            font-weight: bold;
        }

        .setup-link a:hover {
            text-decoration: underline;
        }

        .loading {
            display: none;
            text-align: center;
            margin: 20px 0;
        }

        .loading.show {
            display: block;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4CAF50;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>💰 Sistema Financiero</h1>
            <p>Acceso Seguro</p>
        </div>

        <div class="login-form">
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <?php if (!$usuarios_existen): ?>
                <div class="setup-link">
                    ⚠️ <a href="setup.php">Ejecutar configuración inicial</a>
                </div>
            <?php endif; ?>

            

            <div class="loading" id="loadingDiv">
                <div class="spinner"></div>
                <p>Verificando credenciales...</p>
            </div>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="username">Usuario</label>
                    <div class="input-icon user">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            required 
                            autocomplete="username"
                            placeholder="Ingresa tu usuario"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                            maxlength="50"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-icon password">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Ingresa tu contraseña"
                            maxlength="255"
                        >
                    </div>
                </div>

                <button type="submit" class="login-btn" id="loginBtn">
                    🔑 Iniciar Sesión
                </button>
            </form>

            
        </div>

        <div class="security-features">
            <h4>🔐 Características de seguridad:</h4>
            <ul>
                <li>Contraseñas encriptadas</li>
                <li>Sesiones seguras con timeout</li>
                <li>Protección contra fuerza bruta</li>
                <li>Validación de sesiones en BD</li>
                <li>Registro de actividad</li>
            </ul>
        </div>
    </div>

    <script>
        // Variables globales
        let loginAttempts = 0;
        const maxAttempts = 3;

        function mostrarAyuda() {
            const demoInfo = document.getElementById('demoInfo');
            if (demoInfo.classList.contains('show')) {
                demoInfo.classList.remove('show');
            } else {
                demoInfo.classList.add('show');
            }
        }

        function fillCredentials(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            
            // Añadir efecto visual
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.style.borderColor = '#4CAF50';
                setTimeout(() => {
                    input.style.borderColor = '#ddd';
                }, 1000);
            });
        }

        // Auto-focus en el primer campo
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });

        // Manejar Enter para enviar formulario
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !document.getElementById('loginBtn').disabled) {
                document.getElementById('loginForm').submit();
            }
        });

        // Validación en tiempo real
        document.getElementById('username').addEventListener('input', function() {
            const username = this.value.trim();
            if (username.length > 0 && username.length < 3) {
                this.style.borderColor = '#ff9800';
            } else if (username.length >= 3) {
                this.style.borderColor = '#4CAF50';
            } else {
                this.style.borderColor = '#ddd';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            if (password.length > 0 && password.length < 6) {
                this.style.borderColor = '#ff9800';
            } else if (password.length >= 6) {
                this.style.borderColor = '#4CAF50';
            } else {
                this.style.borderColor = '#ddd';
            }
        });

        // Manejar envío del formulario
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const loadingDiv = document.getElementById('loadingDiv');
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;

            // Validaciones básicas
            if (!username || !password) {
                e.preventDefault();
                alert('⚠️ Por favor, completa todos los campos');
                return;
            }

            if (username.length < 3) {
                e.preventDefault();
                alert('⚠️ El nombre de usuario debe tener al menos 3 caracteres');
                document.getElementById('username').focus();
                return;
            }

            // Mostrar loading
            btn.disabled = true;
            btn.textContent = '🔄 Verificando...';
            loadingDiv.classList.add('show');
            
            // Timeout de seguridad
            setTimeout(() => {
                if (btn.disabled) {
                    btn.disabled = false;
                    btn.textContent = '🔑 Iniciar Sesión';
                    loadingDiv.classList.remove('show');
                }
            }, 10000); // 10 segundos máximo
        });

        // Contador de intentos fallidos
        <?php if ($tipo_mensaje === 'error'): ?>
        loginAttempts++;
        if (loginAttempts >= maxAttempts) {
            document.getElementById('loginBtn').textContent = '⚠️ Demasiados intentos';
            document.getElementById('loginBtn').style.background = '#f44336';
            
            setTimeout(() => {
                document.getElementById('loginBtn').textContent = '🔑 Iniciar Sesión';
                document.getElementById('loginBtn').style.background = 'linear-gradient(135deg, #4CAF50, #45a049)';
                loginAttempts = 0;
            }, 5000);
        }
        <?php endif; ?>

        // Verificar conexión con el servidor
        function checkServerConnection() {
            fetch('auth.php?action=check')
                .then(response => response.json())
                .then(data => {
                    if (data.authenticated) {
                        window.location.href = 'index.php';
                    }
                })
                .catch(error => {
                    console.log('Server check failed:', error);
                });
        }

        // Verificar cada 30 segundos si hay una sesión activa
        setInterval(checkServerConnection, 30000);

        // Prevenir ataques de fuerza bruta básicos
        let submitCount = 0;
        const originalSubmit = document.getElementById('loginForm').submit;
        
        document.getElementById('loginForm').submit = function() {
            submitCount++;
            if (submitCount > 5) {
                alert('⚠️ Demasiados intentos. Espera un momento antes de intentar nuevamente.');
                return false;
            }
            originalSubmit.call(this);
        };

        // Reset contador después de 5 minutos
        setTimeout(() => {
            submitCount = 0;
        }, 300000);

        // Limpiar campos sensibles al salir
        window.addEventListener('beforeunload', function() {
            document.getElementById('password').value = '';
        });
    </script>
</body>
</html>