# 💰 Sistema de Registro Financiero

Sistema web completo para gestionar ingresos y egresos con reconocimiento de voz avanzado en español, autenticación segura y gestión de usuarios.

## 🚀 Características Principales

- **Registro Manual** - Formulario tradicional para transacciones
- **Control por Voz Avanzado** - Comandos en español natural con procesamiento inteligente
- **Dashboard en Tiempo Real** - Estadísticas y balance automático con actualizaciones en vivo
- **Autenticación Multi-Usuario** - Sistema de login protegido con roles y sesiones seguras
- **Gestión de Transacciones** - Crear, eliminar, filtrar y visualizar registros
- **Panel de Administración** - Gestión completa de usuarios y sistema
- **Responsive** - Compatible con móviles y tablets con optimizaciones específicas
- **Setup Automático** - Configuración inicial inteligente con diagnósticos

## 📋 Requisitos del Sistema

- **PHP 7.4+** (recomendado PHP 8.0+)
- **MySQL 5.7+ / MariaDB 10.3+**
- **Servidor web** (Apache/Nginx) con mod_rewrite
- **Navegador moderno** con soporte para micrófono
- **HTTPS** (recomendado para reconocimiento de voz)
- **Extensiones PHP**: PDO, PDO_MySQL, JSON

## ⚡ Instalación Rápida

### 1. Configurar Base de Datos
```sql
CREATE DATABASE finanzas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'finanzas'@'localhost' IDENTIFIED BY 'tu_password_seguro_2024!';
GRANT ALL PRIVILEGES ON finanzas.* TO 'finanzas'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configurar Conexión
Editar `database.php`:
```php
private $host = 'localhost';
private $db_name = 'finanzas';
private $username = 'finanzas';
private $password = 'tu_password_seguro_2024!';
```

### 3. Ejecutar Setup Inicial
```
1. Subir archivos al servidor
2. Acceder a: https://tu-dominio.com/setup.php
3. Ejecutar "Setup Completo" 
4. Verificar diagnósticos del sistema
5. Ir a: https://tu-dominio.com/login.php
```

### 4. Acceso Inicial
```
URL: https://tu-dominio.com/login.php
```

## 🔑 Credenciales por Defecto

| Usuario | Contraseña | Rol | Descripción |
|---------|------------|-----|-------------|
| `admin` | `admin123` | Administrador | Acceso completo + gestión usuarios |
| `usuario` | `usuario123` | Usuario | Gestión de transacciones personales |

> ⚠️ **IMPORTANTE:** Cambiar estas credenciales inmediatamente en producción

## 🎤 Control por Voz - Guía Completa

### ✅ Comandos Optimizados que Funcionan
```bash
# Formatos numéricos básicos
"ingreso de 150000 título salario"
"egreso 25000 título compras del mes"

# Números con separadores (reconocidos por voz)
"ingreso de 150,000 título proyecto freelance"  
"egreso 1,200,000 título auto nuevo"

# Millones decimales 
"ingreso 1.5 millones título negocio"     → $1,500,000
"egreso 0.5 millones título casa"         → $500,000
"ingreso 2.3 millones título inversión"   → $2,300,000

# Millones compuestos (reconocimiento natural)
"egreso un millón 200 000 título inmueble"    → $1,200,000
"ingreso dos millones 500 000 título venta"   → $2,500,000

# Números con espacios (común en móviles)
"ingreso 200 000 título salario mensual"     → $200,000
"egreso 1 500 000 título proyecto grande"    → $1,500,000

# Abreviaciones reconocidas
"ingreso 150k título freelance web"          → $150,000
"egreso 250k título equipos oficina"         → $250,000

# Números en palabras (más confiable)
"ingreso doscientos mil título bonus anual"     → $200,000
"egreso cincuenta mil título reparación auto"   → $50,000
"ingreso quinientos mil título comisión venta"  → $500,000
```

### 🎯 Patrones de Comando Reconocidos
- **Inicio**: `"crear"`, `"nuevo"`, `"ingreso"`, `"egreso"`
- **Monto**: Números en texto o dígitos + `"mil"`, `"millones"`
- **Título**: `"título"`, `"con título"`, `"llamado"`
- **Descripción**: `"descripción"`, `"detalle"` (opcional)

### 📱 Optimización por Dispositivo

#### **Android (Chrome/Firefox)**
```bash
# Configuración recomendada:
- Habla 2x más fuerte que en PC
- Usa Chrome actualizado
- Desactiva "Ok Google" temporalmente
- Verifica: Configuración > Apps > Chrome > Permisos > Micrófono

# Comandos optimizados:
"ingreso ciento cincuenta mil título salario"
"egreso veinticinco mil título supermercado"
"ingreso medio millón título bonus"
```

#### **iOS/iPhone (Safari)**
```bash
# Configuración recomendada:
- Usa Safari (mejor compatibilidad)
- Habla al micrófono inferior del teléfono
- Desactiva Siri temporalmente
- Configurar: Ajustes > Safari > Cámara y Micrófono > Permitir

# Comandos optimizados:
"ingreso doscientos mil título freelance"
"egreso cincuenta mil título compras"
"nuevo ingreso de quinientos mil título proyecto"
```

## 🆕 Funciones Avanzadas del Sistema

### 🗑️ Gestión de Transacciones
- **Eliminar transacciones** con botón X en cada registro
- **Confirmación inteligente** mostrando detalles de la transacción
- **Animaciones suaves** de eliminación con feedback visual
- **Actualización automática** del balance y estadísticas
- **Historial visual** con íconos 🎤 (voz) y 📝 (manual)

### 📊 Dashboard Mejorado
- **Estadísticas en tiempo real**: Total ingresos, egresos, balance
- **Contador de transacciones** con métricas detalladas
- **Balance automático** calculado (ingresos - egresos)
- **Actualización automática** cada 30 segundos
- **Formato de moneda argentina** (ARS) con separadores de miles

### 👥 Sistema Multi-Usuario Completo
- **Roles definidos**: Administrador y Usuario
- **Sesiones seguras** en base de datos con timeout configurable
- **Transacciones por usuario** - cada usuario ve solo sus datos
- **Validación automática** de permisos en cada operación
- **Protección contra fuerza bruta** con bloqueo temporal

### 🔧 Panel de Administración (`user_management.php`)
- **Crear usuarios** con validación completa de datos
- **Activar/Desactivar usuarios** con un clic
- **Resetear contraseñas** con generador automático
- **Desbloquear usuarios** después de intentos fallidos
- **Estadísticas del sistema** en tiempo real
- **Edición de perfil** para usuarios actuales

### 🛠️ Setup Inteligente (`setup.php`)
- **Diagnóstico completo** de base de datos y conexión
- **Creación automática** de todas las tablas necesarias
- **Usuarios demo** incluidos para pruebas
- **Verificación de conectividad** y permisos
- **Limpieza de sesiones** expiradas
- **Estadísticas del sistema** con conteos detallados

## 📁 Estructura Completa de Archivos

```
sistema-financiero/
├── 🔐 Autenticación y Seguridad
│   ├── login.php              # Página de login con diseño moderno
│   ├── auth.php               # Sistema de autenticación completo
│   ├── logout.php             # Cierre de sesión seguro
│   └── user_management.php    # Panel de administración de usuarios
│
├── 🏠 Aplicación Principal
│   ├── index.php              # Dashboard principal con todas las funciones
│   ├── api.php                # API REST completa para el frontend
│   └── database.php           # Configuración y conexión a BD
│
├── ⚙️ Configuración y Herramientas
│   ├── setup.php              # Setup automático con diagnósticos
│   └── test_voice.php         # Test de reconocimiento de voz
│
├── 📄 Documentación
│   ├── README.md              # Documentación completa (este archivo)
│   ├── CHANGELOG.md           # Historial de cambios y versiones
│   └── INSTALL.md             # Guía de instalación detallada
│
└── 🔒 Configuración de Servidor
    ├── .htaccess              # Configuración Apache con seguridad
    ├── .env.example           # Ejemplo de variables de entorno
    └── config/                # Configuraciones adicionales
        └── security.php       # Headers de seguridad
```

## 🛠️ API REST Completa

### 📋 Endpoints Disponibles

#### Autenticación
```http
# Verificar sesión actual
GET /auth.php?action=check
Response: {
  "authenticated": true,
  "user": {
    "id": 1,
    "username": "admin",
    "nombre": "Administrador",
    "rol": "admin"
  },
  "is_admin": true
}

# Cerrar sesión
GET /auth.php?action=logout
Response: { "success": true, "message": "Sesión cerrada" }
```

#### Transacciones
```http
# Obtener resumen financiero
GET /api.php?action=obtener_resumen
Response: {
  "success": true,
  "data": {
    "total_ingresos": 1500000,
    "total_egresos": 850000,
    "balance": 650000,
    "total_transacciones": 25
  }
}

# Obtener lista de transacciones
GET /api.php?action=obtener_transacciones&limit=50
Response: {
  "success": true,
  "data": [
    {
      "id": 1,
      "tipo": "ingreso",
      "monto": 150000,
      "titulo": "Freelance Web",
      "descripcion": "Desarrollo sitio corporativo",
      "metodo_creacion": "manual",
      "fecha_creacion": "2025-08-03 10:30:00"
    }
  ]
}

# Crear transacción manual
POST /api.php
Content-Type: application/json
{
  "action": "crear_transaccion",
  "datos": {
    "tipo": "ingreso",
    "monto": 150000,
    "titulo": "Freelance Proyecto",
    "descripcion": "Desarrollo web para cliente",
    "metodo_creacion": "manual"
  }
}

# Procesar comando de voz
POST /api.php
Content-Type: application/json
{
  "action": "procesar_voz",
  "texto": "ingreso de 150000 título freelance web"
}

# Eliminar transacción
POST /api.php
Content-Type: application/json
{
  "action": "eliminar_transaccion",
  "id": 123
}
```

#### Gestión de Usuarios (Solo Admin)
```http
# Crear usuario
POST /user_management.php
Content-Type: application/x-www-form-urlencoded
action=crear_usuario&username=nuevo_user&password=password123&nombre=Nuevo Usuario&email=nuevo@ejemplo.com&rol=usuario

# Activar/Desactivar usuario
POST /user_management.php
Content-Type: application/x-www-form-urlencoded
action=toggle_usuario&user_id=2

# Resetear contraseña
POST /user_management.php
Content-Type: application/x-www-form-urlencoded
action=reset_password&user_id=2&new_password=nueva_password123
```

## 🔧 Configuración Avanzada

### Variables de Entorno (Recomendado)
```bash
# .env
DB_HOST=localhost
DB_NAME=finanzas
DB_USER=finanzas_user
DB_PASS=password_ultra_seguro_2024!
DB_CHARSET=utf8mb4

# Configuración de sesiones
SESSION_LIFETIME=1800
SESSION_NAME=SISTEMA_FINANCIERO

# Configuración de seguridad
ENABLE_HTTPS_REDIRECT=true
ENABLE_BRUTEFORCE_PROTECTION=true
MAX_LOGIN_ATTEMPTS=5
LOCKOUT_DURATION=900
```

### Configuración Apache (.htaccess)
```apache
# Redirigir a HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Proteger archivos sensibles
<Files "database.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files ".env">
    Order Allow,Deny
    Deny from all
</Files>

# Headers de seguridad
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options SAMEORIGIN
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cacheo de archivos estáticos
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpg "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
</IfModule>
```

### Configuración de Producción
```php
// database.php - Configuración segura para producción
class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    
    public function __construct() {
        // Usar variables de entorno en producción
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->db_name = $_ENV['DB_NAME'] ?? 'finanzas';
        $this->username = $_ENV['DB_USER'] ?? 'finanzas_user';
        $this->password = $_ENV['DB_PASS'] ?? 'password_seguro';
    }
    
    // Configuración SSL para producción
    public function getConnection() {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
        ];
        
        return new PDO($dsn, $this->username, $this->password, $options);
    }
}
```

## 🚀 Guía de Uso del Sistema

### 1. **Instalación y Configuración Inicial**
```bash
1. Crear base de datos MySQL
2. Configurar credenciales en database.php
3. Subir archivos al servidor web
4. Ejecutar setup.php para configuración automática
5. Verificar diagnósticos del sistema
6. Acceder a login.php con credenciales por defecto
```

### 2. **Primer Uso como Administrador**
```bash
1. Login como admin/admin123
2. Ir a "⚙️ Admin" → user_management.php
3. Cambiar contraseña del admin
4. Crear usuarios adicionales
5. Probar creación de transacciones (manual y voz)
6. Verificar estadísticas del dashboard
```

### 3. **Uso Diario del Sistema**
```bash
1. Login con credenciales personales
2. Registrar ingresos y egresos:
   - 📝 Manual: Usar formulario tradicional
   - 🎤 Por voz: Usar comandos de voz en español
3. Revisar dashboard con estadísticas en tiempo real
4. Eliminar/editar transacciones si es necesario
5. Logout automático por seguridad (30 min)
```

### 4. **Gestión de Usuarios (Solo Admin)**
```bash
1. Acceder a user_management.php
2. Crear/editar/desactivar usuarios
3. Resetear contraseñas cuando sea necesario
4. Desbloquear usuarios después de intentos fallidos
5. Monitorear estadísticas de usuarios y sesiones
```

## 📈 Características del Dashboard

### Estadísticas en Tiempo Real
- 💰 **Total Ingresos** - Suma de todos los ingresos del usuario
- 💸 **Total Egresos** - Suma de todos los gastos del usuario
- 📊 **Balance** - Diferencia automática (ingresos - egresos)
- 🧾 **Total Transacciones** - Contador de registros totales

### Lista de Transacciones Avanzada
- 🎤 **Íconos diferenciados** - Manual (📝) vs Por Voz (🎤)
- 📅 **Fecha/hora completa** con formato localizado
- 💵 **Formato moneda argentina** con separadores de miles
- ✕ **Eliminar con confirmación** mostrando detalles
- 🔄 **Actualización automática** cada 30 segundos
- 📱 **Diseño responsive** optimizado para móviles

### Funciones de Seguridad
- 🔒 **Sesiones seguras** con timeout automático
- 🛡️ **Validación de permisos** en cada operación
- 🚫 **Protección contra CSRF** con tokens
- 📝 **Logs de actividad** para auditoría

## 🔍 Diagnóstico y Solución de Problemas

### 🩺 Herramientas de Diagnóstico

#### 1. Setup.php - Diagnóstico Completo
```
https://tu-dominio.com/setup.php

✅ Verificar conexión a base de datos
✅ Comprobar existencia de tablas
✅ Contar usuarios y transacciones
✅ Limpiar sesiones expiradas
✅ Ver estadísticas del sistema
✅ Crear usuarios demo si no existen
```

#### 2. Test_voice.php - Prueba de Reconocimiento
```
https://tu-dominio.com/test_voice.php

✅ Probar micrófono y permisos
✅ Verificar compatibilidad del navegador
✅ Test de comandos específicos
✅ Debug de patrones de reconocimiento
✅ Consejos específicos por dispositivo
```

### 🔧 Problemas Comunes y Soluciones

#### **Reconocimiento de Voz**
| Problema | Causa | Solución |
|----------|-------|----------|
| Error "not-allowed" | Permisos de micrófono denegados | Otorgar permisos en configuración del navegador |
| No reconoce en móvil | Micrófono poco sensible | Hablar 2x más fuerte, usar Safari/Chrome |
| Números incorrectos | Formato no reconocido | Usar: "cincuenta mil", "1.5 millones" |
| Se corta la grabación | No detecta voz | Hablar inmediatamente después del beep |
| Error de red | Problemas de conectividad | Verificar conexión y reintentar |

#### **Base de Datos**
| Error | Causa | Solución |
|-------|-------|----------|
| "Connection failed" | Credenciales incorrectas | Verificar database.php |
| "Table doesn't exist" | Tablas no creadas | Ejecutar setup.php |
| "Access denied" | Permisos MySQL insuficientes | Otorgar permisos al usuario |
| "Character set" | Codificación incorrecta | Usar utf8mb4_unicode_ci |

#### **Autenticación**
| Error | Causa | Solución |
|-------|-------|----------|
| "Session expired" | Tiempo de sesión agotado | Login automático - no requiere acción |
| "Invalid credentials" | Usuario/contraseña incorrectos | Verificar credenciales o resetear |
| "Permission denied" | Rol insuficiente | Verificar rol de usuario en BD |
| "Account locked" | Muchos intentos fallidos | Usar user_management.php para desbloquear |

## 📱 Optimización Móvil Detallada

### 🤖 Android (Chrome/Firefox)
```bash
Configuración Recomendada:
✅ Usar Chrome o Firefox actualizado
✅ Otorgar permisos: Configuración > Apps > Chrome > Micrófono
✅ Desactivar "Ok Google" temporalmente
✅ Conexión WiFi estable (mejor que datos móviles)
✅ Cerrar otras apps que usen micrófono

Técnicas de Voz:
✅ Hablar 2x más fuerte que en PC
✅ Acercarse al micrófono (10-15cm)
✅ Ambiente silencioso
✅ Usar comandos cortos y claros

Comandos Optimizados:
"ingreso ciento cincuenta mil título salario"
"egreso veinticinco mil título supermercado"
"nuevo ingreso medio millón título bonus"
```

### 🍎 iOS/iPhone (Safari)
```bash
Configuración Recomendada:
✅ Usar Safari (mejor compatibilidad que Chrome)
✅ Configurar: Ajustes > Safari > Cámara y Micrófono > Permitir
✅ Desactivar Siri temporalmente
✅ Hablar al micrófono inferior del teléfono
✅ Asegurar iOS actualizado

Técnicas de Voz:
✅ Hablar directamente al micrófono inferior
✅ Pronunciar claramente cada palabra
✅ Usar números en palabras: "doscientos mil"
✅ Pausar 1 segundo después del beep

Comandos Optimizados:
"ingreso doscientos mil título freelance"
"egreso cincuenta mil título compras casa"
"crear ingreso quinientos mil título proyecto"
```

## 🔒 Seguridad y Mejores Prácticas

### Para Producción
```bash
Configuración Obligatoria:
- [ ] Cambiar todas las credenciales por defecto
- [ ] Configurar HTTPS obligatorio con certificado válido
- [ ] Configurar backup automático de base de datos
- [ ] Habilitar logs de acceso y error
- [ ] Configurar firewall del servidor
- [ ] Actualizar contraseñas regularmente
- [ ] Usar variables de entorno para credenciales
- [ ] Configurar límites de intentos de login
```

### Monitoreo Continuo
```bash
Verificaciones Regular:
- [ ] Revisar logs de error PHP y MySQL
- [ ] Monitorear espacio en disco
- [ ] Verificar sesiones activas sospechosas
- [ ] Backup regular de transacciones
- [ ] Actualizar dependencias del sistema
- [ ] Revisar accesos administrativos
```

### Headers de Seguridad
```php
// Agregar en todos los archivos PHP
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Para API endpoints
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
```

## 🆘 Soporte y Debug Avanzado

### Orden de Diagnóstico Recomendado
1. **setup.php** - Verificar configuración general del sistema
2. **test_voice.php** - Probar funcionalidad de reconocimiento de voz
3. **Logs del servidor** - Revisar `/var/log/apache2/error.log`
4. **Consola del navegador** - Verificar errores JavaScript
5. **Red y conectividad** - Verificar respuestas de la API

### Activar Debug Detallado (Temporalmente)
```php
// Agregar al inicio de los archivos PHP para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php_errors.log');

// Para debug de SQL
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

### Logs de Actividad Personalizados
```php
// Función para logging personalizado
function logActivity($usuario_id, $accion, $detalles = '') {
    $log = date('Y-m-d H:i:s') . " - Usuario $usuario_id - $accion - $detalles\n";
    file_put_contents('logs/activity.log', $log, FILE_APPEND | LOCK_EX);
}

// Uso en el código
logActivity($_SESSION['user_id'], 'TRANSACCION_CREADA', 'Monto: $150000');
```

## 📊 Estadísticas y Métricas

### Dashboard de Administrador
- 👥 **Usuarios totales** con estado activo/inactivo
- 💰 **Transacciones globales** por tipo y método
- 📈 **Crecimiento mensual** de usuarios y transacciones
- 🎤 **Uso de reconocimiento de voz** vs manual
- 🕒 **Sesiones activas** y tiempo promedio de uso

### Métricas de Rendimiento
- ⚡ **Tiempo de respuesta** de la API
- 🔄 **Tasa de éxito** del reconocimiento de voz
- 📱 **Distribución por dispositivos** (móvil vs desktop)
- 🌐 **Navegadores más utilizados**
- 💡 **Funciones más populares**

## 🏆 Sistema Listo para Producción

**✅ Instalación completamente automatizada**  
**✅ Reconocimiento de voz avanzado con IA**  
**✅ Dashboard en tiempo real con métricas**  
**✅ Seguridad multi-nivel con auditoría**  
**✅ Panel de administración completo**  
**✅ Compatible con todos los dispositivos**  
**✅ Documentación técnica completa**  
**✅ API REST para integraciones**  
**✅ Sistema de backup y recuperación**  
**✅ Monitoreo y diagnósticos integrados**

---

## 📞 Información del Proyecto

**Nombre**: Sistema de Registro Financiero  
**Versión**: 2.5 (Agosto 2025)  
**Tecnologías**: PHP 8, MySQL 8, JavaScript ES6+, HTML5, CSS3  
**Licencia**: MIT License  
**Compatibilidad**: Chrome 90+, Firefox 88+, Safari 14+, Edge 90+  

**Características Únicas**:
- 🎤 Reconocimiento de voz en español con 15+ patrones optimizados
- 📱 Optimización específica para Android e iOS
- 🔐 Sistema de autenticación con protección contra fuerza bruta
- 📊 Dashboard en tiempo real con actualización automática
- 👥 Gestión multi-usuario con roles y permisos
- 🛠️ Setup automático con diagnósticos inteligentes

**Estado**: ✅ **LISTO PARA PRODUCCIÓN**