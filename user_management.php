<?php
// user_management.php - Gestión de usuarios (solo administradores)

require_once 'auth.php';
$auth = new Auth();
$auth->requerirAdmin(); // Solo administradores pueden acceder

require_once 'database.php';
$database = new Database();
$conn = $database->getConnection();

// Obtener usuario actual ANTES de procesar acciones
$usuario_actual = $auth->obtenerUsuarioActual();

$mensaje = '';
$tipo_mensaje = '';

// Procesar acciones
if ($_POST && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'crear_usuario':
            if (isset($_POST['username'], $_POST['password'], $_POST['nombre'], $_POST['email'], $_POST['rol'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $nombre = trim($_POST['nombre']);
                $email = trim($_POST['email']);
                $rol = $_POST['rol'];
                
                if (!empty($username) && !empty($password) && !empty($nombre) && !empty($email)) {
                    try {
                        $stmt = $conn->prepare("
                            INSERT INTO usuarios (username, password, nombre, email, rol) 
                            VALUES (?, ?, ?, ?, ?)
                        ");
                        
                        $password_hash = password_hash($password, PASSWORD_DEFAULT);
                        
                        if ($stmt->execute([$username, $password_hash, $nombre, $email, $rol])) {
                            $mensaje = "✅ Usuario '$username' creado correctamente";
                            $tipo_mensaje = 'success';
                        } else {
                            $mensaje = "❌ Error al crear usuario '$username'";
                            $tipo_mensaje = 'error';
                        }
                    } catch (Exception $e) {
                        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                            $mensaje = "❌ El usuario '$username' ya existe";
                        } else {
                            $mensaje = "❌ Error: " . $e->getMessage();
                        }
                        $tipo_mensaje = 'error';
                    }
                } else {
                    $mensaje = "❌ Todos los campos son obligatorios";
                    $tipo_mensaje = 'error';
                }
            }
            break;
            
        case 'toggle_usuario':
            if (isset($_POST['user_id'])) {
                $user_id = (int)$_POST['user_id'];
                try {
                    $stmt = $conn->prepare("UPDATE usuarios SET activo = NOT activo WHERE id = ?");
                    if ($stmt->execute([$user_id])) {
                        $mensaje = "✅ Estado del usuario actualizado";
                        $tipo_mensaje = 'success';
                    } else {
                        $mensaje = "❌ Error al actualizar usuario";
                        $tipo_mensaje = 'error';
                    }
                } catch (Exception $e) {
                    $mensaje = "❌ Error: " . $e->getMessage();
                    $tipo_mensaje = 'error';
                }
            }
            break;
            
        case 'reset_password':
            if (isset($_POST['user_id'], $_POST['new_password'])) {
                $user_id = (int)$_POST['user_id'];
                $new_password = $_POST['new_password'];
                
                if (strlen($new_password) >= 6) {
                    try {
                        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare("
                            UPDATE usuarios 
                            SET password = ?, intentos_fallidos = 0, bloqueado_hasta = NULL 
                            WHERE id = ?
                        ");
                        
                        if ($stmt->execute([$password_hash, $user_id])) {
                            $mensaje = "✅ Contraseña actualizada correctamente";
                            $tipo_mensaje = 'success';
                        } else {
                            $mensaje = "❌ Error al actualizar contraseña";
                            $tipo_mensaje = 'error';
                        }
                    } catch (Exception $e) {
                        $mensaje = "❌ Error: " . $e->getMessage();
                        $tipo_mensaje = 'error';
                    }
                } else {
                    $mensaje = "❌ La contraseña debe tener al menos 6 caracteres";
                    $tipo_mensaje = 'error';
                }
            }
            break;
            
        case 'desbloquear_usuario':
            if (isset($_POST['user_id'])) {
                $user_id = (int)$_POST['user_id'];
                try {
                    $stmt = $conn->prepare("
                        UPDATE usuarios 
                        SET intentos_fallidos = 0, bloqueado_hasta = NULL 
                        WHERE id = ?
                    ");
                    
                    if ($stmt->execute([$user_id])) {
                        $mensaje = "✅ Usuario desbloqueado correctamente";
                        $tipo_mensaje = 'success';
                    } else {
                        $mensaje = "❌ Error al desbloquear usuario";
                        $tipo_mensaje = 'error';
                    }
                } catch (Exception $e) {
                    $mensaje = "❌ Error: " . $e->getMessage();
                    $tipo_mensaje = 'error';
                }
            }
            break;
            
        case 'actualizar_perfil':
    if (isset($_POST['user_id'], $_POST['nombre'], $_POST['email'])) {
        $user_id = (int)$_POST['user_id'];
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        
        // Solo permitir actualizar su propio perfil
        if ($user_id == $usuario_actual['id']) {
            if (!empty($nombre) && !empty($email)) {
                try {
                    $stmt = $conn->prepare("
                        UPDATE usuarios 
                        SET nombre = ?, email = ? 
                        WHERE id = ?
                    ");
                    
                    if ($stmt->execute([$nombre, $email, $user_id])) {
                        $mensaje = "✅ Perfil actualizado correctamente";
                        $tipo_mensaje = 'success';
                    } else {
                        $mensaje = "❌ Error al actualizar perfil";
                        $tipo_mensaje = 'error';
                    }
                } catch (Exception $e) {
                    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                        $mensaje = "❌ El email ya está en uso";
                    } else {
                        $mensaje = "❌ Error: " . $e->getMessage();
                    }
                    $tipo_mensaje = 'error';
                }
            } else {
                $mensaje = "❌ El nombre y email son obligatorios";
                $tipo_mensaje = 'error';
            }
        } else {
            $mensaje = "❌ Solo puedes actualizar tu propio perfil";
            $tipo_mensaje = 'error';
        }
    }
    break;
    }
}

// Obtener usuarios
$usuarios = [];
try {
    $stmt = $conn->query("
        SELECT id, username, nombre, email, rol, activo, fecha_creacion, ultimo_acceso, 
               intentos_fallidos, bloqueado_hasta
        FROM usuarios 
        ORDER BY fecha_creacion DESC
    ");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $mensaje = "❌ Error al cargar usuarios: " . $e->getMessage();
    $tipo_mensaje = 'error';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Sistema Financiero</title>
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
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            position: relative;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .user-info {
            position: absolute;
            top: 20px;
            right: 30px;
            font-size: 14px;
            background: rgba(255,255,255,0.1);
            padding: 10px 15px;
            border-radius: 25px;
        }

        .nav-links {
            margin-top: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255,255,255,0.1);
            transition: all 0.3s;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
        }

        .content {
            padding: 40px;
        }

        .mensaje {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .mensaje.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .mensaje.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-card {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }

        .form-card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.3em;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
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

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
        }

        .btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            margin: 2px;
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #333;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-blocked {
            background: #fff3cd;
            color: #856404;
        }

        .role-admin {
            color: #dc3545;
            font-weight: bold;
        }

        .role-user {
            color: #28a745;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        @media (max-width: 768px) {
            .header {
                padding: 20px;
                text-align: center;
            }

            .user-info {
                position: static;
                margin-top: 20px;
                text-align: center;
            }

            .content {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">

            <div class="user-info">
                👤 <?php echo htmlspecialchars($usuario_actual['nombre']); ?> (<?php echo htmlspecialchars($usuario_actual['username']); ?>)
                <a href="auth.php?action=logout" style="color: white; margin-left: 10px;">🚪 Salir</a>
            </div>
             <br>
            <br>           
            <h1>👥 Gestión de Usuarios</h1>
            <p>Administración de usuarios del sistema</p>
            
            <div class="nav-links">
                <a href="index.php">🏠 Inicio</a>
                <a href="setup.php">🔧 Setup</a>
                <a href="#" onclick="mostrarEstadisticas()">📊 Estadísticas</a>
            </div>
        </div>

        <div class="content">
            <?php if ($mensaje): ?>
                <div class="mensaje <?php echo $tipo_mensaje; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>

            <!-- Formulario para crear nuevo usuario -->
            <div class="form-card">
                <h3>➕ Crear Nuevo Usuario</h3>
                
                <form method="POST" id="createUserForm">
                    <input type="hidden" name="action" value="crear_usuario">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>👤 Usuario:</label>
                            <input type="text" name="username" required maxlength="50" 
                                   placeholder="nombre_usuario" pattern="[a-zA-Z0-9_]+" 
                                   title="Solo letras, números y guiones bajos">
                        </div>
                        
                        <div class="form-group">
                            <label>🔐 Contraseña:</label>
                            <input type="password" name="password" required minlength="6" 
                                   placeholder="Mínimo 6 caracteres">
                        </div>
                        
                        <div class="form-group">
                            <label>📝 Nombre Completo:</label>
                            <input type="text" name="nombre" required maxlength="100" 
                                   placeholder="Nombre y Apellido">
                        </div>
                        
                        <div class="form-group">
                            <label>📧 Email:</label>
                            <input type="email" name="email" required maxlength="100" 
                                   placeholder="usuario@ejemplo.com">
                        </div>
                        
                        <div class="form-group">
                            <label>👑 Rol:</label>
                            <select name="rol" required>
                                <option value="usuario">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn">
                        ➕ Crear Usuario
                    </button>
                </form>
            </div>

            <!-- Lista de usuarios -->
            <div class="form-card">
                <h3>📋 Usuarios del Sistema (<?php echo count($usuarios); ?>)</h3>
                
                <?php if (!empty($usuarios)): ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Información</th>
                                    <th>Rol</th>
                                    <th>Estado</th>
                                    <th>Último Acceso</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $user): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                            <?php if ($user['rol'] === 'admin'): ?>
                                                <span title="Administrador">👑</span>
                                            <?php endif; ?>
                                            <?php if ($user['id'] == $usuario_actual['id']): ?>
                                                <span title="Tu cuenta" style="color: #007bff;">👤</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div><strong><?php echo htmlspecialchars($user['nombre']); ?></strong></div>
                                            <small style="color: #666;"><?php echo htmlspecialchars($user['email']); ?></small>
                                        </td>
                                        <td>
                                            <span class="<?php echo $user['rol'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                                                <?php echo $user['rol'] === 'admin' ? '👑 Admin' : '👤 Usuario'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $bloqueado = $user['bloqueado_hasta'] && strtotime($user['bloqueado_hasta']) > time();
                                            
                                            if ($bloqueado): ?>
                                                <span class="status-badge status-blocked">🔒 Bloqueado</span>
                                                <br><small>Hasta: <?php echo date('d/m/Y H:i', strtotime($user['bloqueado_hasta'])); ?></small>
                                            <?php elseif ($user['activo']): ?>
                                                <span class="status-badge status-active">✅ Activo</span>
                                                <?php if ($user['intentos_fallidos'] > 0): ?>
                                                    <br><small style="color: #856404;">Intentos: <?php echo $user['intentos_fallidos']; ?></small>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="status-badge status-inactive">❌ Inactivo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($user['ultimo_acceso']) {
                                                $ultimo_acceso = strtotime($user['ultimo_acceso']);
                                                $diferencia = time() - $ultimo_acceso;
                                                
                                                echo date('d/m/Y H:i', $ultimo_acceso);
                                                
                                                if ($diferencia < 3600) {
                                                    echo '<br><small style="color: #28a745;">Hace ' . floor($diferencia/60) . ' min</small>';
                                                } elseif ($diferencia < 86400) {
                                                    echo '<br><small style="color: #ffc107;">Hace ' . floor($diferencia/3600) . ' horas</small>';
                                                } elseif ($diferencia < 604800) {
                                                    echo '<br><small style="color: #fd7e14;">Hace ' . floor($diferencia/86400) . ' días</small>';
                                                } else {
                                                    echo '<br><small style="color: #6c757d;">Hace mucho</small>';
                                                }
                                            } else {
                                                echo '<span style="color: #6c757d;">Nunca</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($user['id'] != $usuario_actual['id']): // Acciones para otros usuarios ?>
                                                
                                                <!-- Toggle Activo/Inactivo -->
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="action" value="toggle_usuario">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                    <button type="submit" class="btn btn-small btn-warning" 
                                                            title="<?php echo $user['activo'] ? 'Desactivar' : 'Activar'; ?> usuario"
                                                            onclick="return confirm('¿<?php echo $user['activo'] ? 'Desactivar' : 'Activar'; ?> usuario?')">
                                                        <?php echo $user['activo'] ? '⏸️' : '▶️'; ?>
                                                    </button>
                                                </form>

                                                <!-- Resetear contraseña -->
                                                <button class="btn btn-small btn-info" 
                                                        onclick="mostrarResetPassword(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')"
                                                        title="Cambiar contraseña">
                                                    🔑
                                                </button>

                                                <?php if ($bloqueado): ?>
                                                    <!-- Desbloquear usuario -->
                                                    <form method="POST" style="display: inline;">
                                                        <input type="hidden" name="action" value="desbloquear_usuario">
                                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                        <button type="submit" class="btn btn-small btn-danger" 
                                                                title="Desbloquear usuario"
                                                                onclick="return confirm('¿Desbloquear usuario?')">
                                                            🔓
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                            <?php else: ?>
    <!-- Acciones para tu propia cuenta -->
    <button class="btn btn-small btn-info" 
            onclick="mostrarEditarPerfil(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nombre']); ?>', '<?php echo htmlspecialchars($user['email']); ?>')"
            title="Editar tu perfil">
        ✏️ Editar
    </button>
    
    <button class="btn btn-small btn-warning" 
            onclick="mostrarResetPassword(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')"
            title="Cambiar tu contraseña">
        🔑 Cambiar
    </button>
    
    <small style="color: #28a745; font-style: italic;">Tu cuenta</small>
<?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 40px;">
                        📭 No hay usuarios registrados
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal para resetear contraseña -->
    <div id="resetPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3>🔑 Cambiar Contraseña</h3>
            
            <form method="POST" id="resetPasswordForm">
                <input type="hidden" name="action" value="reset_password">
                <input type="hidden" name="user_id" id="resetUserId">
                
                <div class="form-group">
                    <label>Usuario:</label>
                    <input type="text" id="resetUsername" readonly style="background: #f8f9fa;">
                </div>
                
                <div class="form-group">
                    <label>Nueva Contraseña:</label>
                    <input type="password" name="new_password" required minlength="6" 
                           placeholder="Mínimo 6 caracteres" id="newPassword">
                </div>
                
                <div class="form-group">
                    <label>Confirmar Contraseña:</label>
                    <input type="password" required minlength="6" 
                           placeholder="Repetir contraseña" id="confirmPassword">
                </div>
                
                <button type="submit" class="btn">🔄 Cambiar Contraseña</button>
                <button type="button" class="btn btn-danger" onclick="cerrarModal()">❌ Cancelar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal para editar perfil -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModalPerfil()">&times;</span>
        <h3>✏️ Editar Mi Perfil</h3>
        
        <form method="POST" id="editProfileForm">
            <input type="hidden" name="action" value="actualizar_perfil">
            <input type="hidden" name="user_id" id="editUserId">
            
            <div class="form-group">
                <label>📝 Nombre Completo:</label>
                <input type="text" name="nombre" id="editNombre" required maxlength="100" 
                       placeholder="Tu nombre completo">
            </div>
            
            <div class="form-group">
                <label>📧 Email:</label>
                <input type="email" name="email" id="editEmail" required maxlength="100" 
                       placeholder="tu@email.com">
            </div>
            
            <div style="background: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border: 1px solid #ffeaa7;">
                <small style="color: #856404;">
                    <strong>Nota:</strong> No puedes cambiar tu nombre de usuario. 
                    Para cambiar tu contraseña, usa el botón "🔑 Cambiar" correspondiente.
                </small>
            </div>
            
            <button type="submit" class="btn">💾 Guardar Cambios</button>
            <button type="button" class="btn btn-danger" onclick="cerrarModalPerfil()">❌ Cancelar</button>
        </form>
    </div>
</div>

    <script>
        // Validación del formulario de crear usuario
        document.getElementById('createUserForm').addEventListener('submit', function(e) {
            const username = this.querySelector('[name="username"]').value.trim();
            const password = this.querySelector('[name="password"]').value;
            const email = this.querySelector('[name="email"]').value.trim();
            
            if (username.length < 3) {
                e.preventDefault();
                alert('⚠️ El nombre de usuario debe tener al menos 3 caracteres');
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                alert('⚠️ El usuario solo puede contener letras, números y guiones bajos');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('⚠️ La contraseña debe tener al menos 6 caracteres');
                return;
            }
            
            if (!email.includes('@') || !email.includes('.')) {
                e.preventDefault();
                alert('⚠️ Ingresa un email válido');
                return;
            }
            
            if (!confirm(`¿Crear usuario "${username}"?`)) {
                e.preventDefault();
                return;
            }
        });

        // Funciones del modal
        function mostrarResetPassword(userId, username) {
            document.getElementById('resetUserId').value = userId;
            document.getElementById('resetUsername').value = username;
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
            document.getElementById('resetPasswordModal').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('resetPasswordModal').style.display = 'none';
        }

        // Validación del formulario de reset password
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('⚠️ Las contraseñas no coinciden');
                return;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('⚠️ La contraseña debe tener al menos 6 caracteres');
                return;
            }
            
            if (!confirm('¿Cambiar la contraseña del usuario?')) {
                e.preventDefault();
                return;
            }
        });

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
    const resetModal = document.getElementById('resetPasswordModal');
    const profileModal = document.getElementById('editProfileModal');
    
    if (event.target == resetModal) {
        cerrarModal();
    }
    if (event.target == profileModal) {
        cerrarModalPerfil();
    }
}
        
        // Funciones para editar perfil
function mostrarEditarPerfil(userId, nombre, email) {
    document.getElementById('editUserId').value = userId;
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEmail').value = email;
    document.getElementById('editProfileModal').style.display = 'block';
}

function cerrarModalPerfil() {
    document.getElementById('editProfileModal').style.display = 'none';
}

// Validación del formulario de editar perfil
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    const nombre = document.getElementById('editNombre').value.trim();
    const email = document.getElementById('editEmail').value.trim();
    
    if (nombre.length < 2) {
        e.preventDefault();
        alert('⚠️ El nombre debe tener al menos 2 caracteres');
        return;
    }
    
    if (!email.includes('@') || !email.includes('.')) {
        e.preventDefault();
        alert('⚠️ Ingresa un email válido');
        return;
    }
    
    if (!confirm('¿Guardar los cambios en tu perfil?')) {
        e.preventDefault();
        return;
    }
});

        // Función para mostrar estadísticas
        function mostrarEstadisticas() {
            const totalUsuarios = <?php echo count($usuarios); ?>;
            const usuariosActivos = <?php echo count(array_filter($usuarios, function($u) { return $u['activo']; })); ?>;
            const administradores = <?php echo count(array_filter($usuarios, function($u) { return $u['rol'] === 'admin'; })); ?>;
            const bloqueados = <?php echo count(array_filter($usuarios, function($u) { return $u['bloqueado_hasta'] && strtotime($u['bloqueado_hasta']) > time(); })); ?>;
            
            const stats = `
📊 ESTADÍSTICAS DE USUARIOS

👥 Total de usuarios: ${totalUsuarios}
✅ Usuarios activos: ${usuariosActivos}
❌ Usuarios inactivos: ${totalUsuarios - usuariosActivos}
👑 Administradores: ${administradores}
👤 Usuarios regulares: ${totalUsuarios - administradores}
🔒 Usuarios bloqueados: ${bloqueados}

💡 Ratio activos: ${totalUsuarios > 0 ? Math.round((usuariosActivos/totalUsuarios)*100) : 0}%
            `;
            
            alert(stats);
        }

        // Auto-refresh cada 5 minutos
        setInterval(() => {
            fetch('auth.php?action=check')
                .then(response => response.json())
                .then(data => {
                    if (!data.authenticated || !data.is_admin) {
                        alert('⚠️ Sesión expirada o permisos insuficientes. Redirigiendo...');
                        window.location.href = 'login.php';
                    }
                })
                .catch(error => console.log('Check session failed:', error));
        }, 300000); // 5 minutos

        // Confirmaciones adicionales para acciones críticas
        document.querySelectorAll('form').forEach(form => {
            const action = form.querySelector('input[name="action"]');
            if (action && action.value === 'toggle_usuario') {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button');
                    button.disabled = true;
                    button.textContent = '⏳';
                    
                    setTimeout(() => {
                        button.disabled = false;
                        button.textContent = button.title.includes('Desactivar') ? '⏸️' : '▶️';
                    }, 3000);
                });
            }
        });

        // Generar contraseña aleatoria
        function generarPassword() {
            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let password = '';
            for (let i = 0; i < 8; i++) {
                password += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return password;
        }

        // Agregar botón de generar contraseña en el modal
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('newPassword');
            const generateBtn = document.createElement('button');
            generateBtn.type = 'button';
            generateBtn.className = 'btn btn-small btn-info';
            generateBtn.innerHTML = '🎲 Generar';
            generateBtn.style.marginLeft = '10px';
            generateBtn.onclick = function() {
                const newPass = generarPassword();
                passwordField.value = newPass;
                document.getElementById('confirmPassword').value = newPass;
                alert(`Contraseña generada: ${newPass}\n\n¡Cópiala antes de continuar!`);
            };
            
            passwordField.parentNode.appendChild(generateBtn);
        });
    </script>
</body>
</html>