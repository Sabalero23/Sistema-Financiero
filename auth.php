<?php
// auth.php - Middleware de autenticación con base de datos - VERSIÓN CORREGIDA
session_start();

// Evitar cualquier output antes del JSON
ob_start();

require_once 'database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function login($username, $password) {
        if (!$this->db) {
            return [
                'success' => false,
                'message' => 'Error de conexión a la base de datos'
            ];
        }
        
        $database = new Database();
        $usuario = $database->authenticateUser($username, $password);
        
        if ($usuario) {
            // Generar ID de sesión único
            $session_id = session_id();
            
            // Almacenar datos en la sesión
            $_SESSION['logged_in'] = true;
            $_SESSION['session_id'] = $session_id;
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['rol'] = $usuario['rol'];
            $_SESSION['login_time'] = time();
            
            // Crear registro de sesión en BD
            $ip_address = $this->getClientIP();
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $database->createSession($session_id, $usuario['id'], $ip_address, $user_agent);
            
            return [
                'success' => true,
                'user' => $usuario
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Usuario o contraseña incorrectos'
        ];
    }
    
    public function verificarAutenticacion() {
        // Verificar si el usuario está logueado
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }
        
        // Si no hay conexión a BD, permitir sesión básica por 5 minutos
        if (!$this->db) {
            $timeout = 300; // 5 minutos
            if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
                $this->logout();
                return false;
            }
            return true;
        }
        
        // Verificar sesión en base de datos
        if (isset($_SESSION['session_id'])) {
            $database = new Database();
            $session_data = $database->validateSession($_SESSION['session_id']);
            
            if (!$session_data) {
                // Sesión inválida o expirada
                $this->logout();
                return false;
            }
            
            // Actualizar datos de sesión si es necesario
            $_SESSION['user_id'] = $session_data['usuario_id'];
            $_SESSION['username'] = $session_data['username'];
            $_SESSION['nombre'] = $session_data['nombre'];
            $_SESSION['email'] = $session_data['email'];
            $_SESSION['rol'] = $session_data['rol'];
        }
        
        // Verificar timeout de sesión (30 minutos)
        $timeout = 1800; // 30 minutos en segundos
        if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout) {
            // Sesión expirada
            $this->logout();
            return false;
        }
        
        // Actualizar tiempo de última actividad
        $_SESSION['login_time'] = time();
        
        return true;
    }
    
    public function requerirAutenticacion() {
        if (!$this->verificarAutenticacion()) {
            // Si es una petición AJAX/API, devolver JSON
            if ($this->isAjaxRequest()) {
                // Limpiar cualquier output previo
                if (ob_get_level()) {
                    ob_end_clean();
                }
                
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Sesión expirada. Redirigiendo al login...',
                    'redirect' => 'login.php',
                    'authenticated' => false
                ]);
                exit;
            } else {
                // Redireccionar al login
                header('Location: login.php');
                exit;
            }
        }
    }
    
    public function obtenerUsuarioActual() {
        if ($this->verificarAutenticacion()) {
            return [
                'id' => $_SESSION['user_id'] ?? 1,
                'username' => $_SESSION['username'] ?? 'usuario',
                'nombre' => $_SESSION['nombre'] ?? 'Usuario',
                'email' => $_SESSION['email'] ?? 'usuario@sistema.com',
                'rol' => $_SESSION['rol'] ?? 'usuario',
                'login_time' => $_SESSION['login_time'] ?? time()
            ];
        }
        return null;
    }
    
    public function logout() {
        // Marcar sesión como inactiva en BD
        if (isset($_SESSION['session_id']) && $this->db) {
            $database = new Database();
            $database->destroySession($_SESSION['session_id']);
        }
        
        // Destruir sesión PHP
        session_destroy();
        
        // Redireccionar al login solo si no es una petición AJAX
        if (!$this->isAjaxRequest() && !headers_sent()) {
            header('Location: login.php');
            exit;
        }
    }
    
    public function isAdmin() {
        $usuario = $this->obtenerUsuarioActual();
        return $usuario && $usuario['rol'] === 'admin';
    }
    
    public function requerirAdmin() {
        $this->requerirAutenticacion();
        
        if (!$this->isAdmin()) {
            if ($this->isAjaxRequest()) {
                // Limpiar cualquier output previo
                if (ob_get_level()) {
                    ob_end_clean();
                }
                
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Acceso denegado. Se requieren permisos de administrador.'
                ]);
                exit;
            } else {
                http_response_code(403);
                echo "<!DOCTYPE html>
                <html>
                <head>
                    <title>Acceso Denegado</title>
                    <style>
                        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                        .error { color: #f44336; }
                        .btn { background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <h1 class='error'>⚠️ Acceso Denegado</h1>
                    <p>No tienes permisos suficientes para acceder a esta página.</p>
                    <a href='index.php' class='btn'>🏠 Volver al Inicio</a>
                </body>
                </html>";
                exit;
            }
        }
    }
    
    private function isAjaxRequest() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    private function getClientIP() {
        $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, 
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
}

// Funciones de compatibilidad (para código existente)
function verificarAutenticacion() {
    $auth = new Auth();
    return $auth->verificarAutenticacion();
}

function requerirAutenticacion() {
    $auth = new Auth();
    return $auth->requerirAutenticacion();
}

function obtenerUsuarioActual() {
    $auth = new Auth();
    return $auth->obtenerUsuarioActual();
}

function cerrarSesion() {
    $auth = new Auth();
    return $auth->logout();
}

function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// Si se llama directamente este archivo
if (basename($_SERVER['PHP_SELF']) === 'auth.php') {
    $auth = new Auth();
    
    if (isset($_GET['action'])) {
        // Limpiar output buffer antes de enviar JSON
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        switch ($_GET['action']) {
            case 'logout':
                $auth->logout();
                break;
                
            case 'check':
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'authenticated' => $auth->verificarAutenticacion(),
                    'user' => $auth->obtenerUsuarioActual(),
                    'is_admin' => $auth->isAdmin(),
                    'timestamp' => time()
                ]);
                exit;
                
            case 'status':
                header('Content-Type: application/json; charset=utf-8');
                $usuario = $auth->obtenerUsuarioActual();
                if ($usuario) {
                    echo json_encode([
                        'success' => true,
                        'user' => $usuario,
                        'session_time_remaining' => 1800 - (time() - $usuario['login_time']),
                        'timestamp' => time()
                    ]);
                } else {
                    echo json_encode([
                        'success' => false, 
                        'message' => 'No autenticado',
                        'timestamp' => time()
                    ]);
                }
                exit;
                
            default:
                $auth->requerirAutenticacion();
        }
    } else {
        $auth->requerirAutenticacion();
    }
}
?>