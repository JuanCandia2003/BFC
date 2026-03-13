<?php
/**
 * Configuración General del Proyecto BFC
 * 
 * Este archivo contiene las constantes y configuraciones
 * globales del sistema.
 */

// ============================================
// CONFIGURACIÓN DE LA APLICACIÓN
// ============================================

define('APP_NAME', 'Ballet Folclórico de Cochabamba');
define('APP_SHORT_NAME', 'BFC');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // production | development

// ============================================
// CONFIGURACIÓN DE LA BASE DE DATOS
// ============================================

define('DB_HOST', 'db');
define('DB_NAME', 'BFC');
define('DB_USER', 'usuario');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8mb4');

// ============================================
// CONFIGURACIÓN DE RUTAS
// ============================================

$scriptDir = $_SERVER['SCRIPT_FILENAME'] ?? '';
if (strpos($scriptDir, '/public/') !== false || strpos($scriptDir, '\\public\\') !== false) {
    define('BASE_PATH', dirname(dirname($scriptDir)));
} elseif (strpos($scriptDir, '/public') !== false || strpos($scriptDir, '\\public') !== false) {
    define('BASE_PATH', dirname($scriptDir));
} else {
    define('BASE_PATH', dirname(__DIR__));
}
define('PUBLIC_PATH', BASE_PATH . '/public');
define('SRC_PATH', BASE_PATH . '/src');
define('ASSETS_PATH', BASE_PATH . '/assets');
define('IMAGES_PATH', ASSETS_PATH . '/images');

// ============================================
// CONFIGURACIÓN DE SEGURIDAD
// ============================================

define('SESSION_NAME', 'BFC_SESSION');
define('SESSION_LIFETIME', 3600); // 1 hora en segundos
define('CSRF_TOKEN_NAME', 'csrf_token');

// Configuración de cookies
define('COOKIE_OPTIONS', [
    'expires' => time() + (86400 * 30), // 30 días
    'path' => '/',
    'secure' => false, // true en producción con HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

// ============================================
// CONFIGURACIÓN DE SUBIDAS
// ============================================

define('UPLOAD_MAX_SIZE', 5242880); // 5MB en bytes
define('UPLOAD_ALLOWED_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp'
]);
define('UPLOAD_PATH', BASE_PATH . '/assets/images/');

// ============================================
// CONFIGURACIÓN DE PAGINACIÓN
// ============================================

define('ITEMS_PER_PAGE', 20);
define('MAX_PAGINATION_LINKS', 5);

// ============================================
// ROLES DE USUARIO
// ============================================

define('ROLE_ADMIN', 'admin');
define('ROLE_BAILARIN', 'bailarin');

// ============================================
// ESTADOS DE PRÉSTAMO
// ============================================

define('LOAN_STATUS', [
    'pendiente' => 'Pendiente',
    'aprobado' => 'Aprobado',
    'devuelto' => 'Devuelto',
    'rechazado' => 'Rechazado'
]);

// ============================================
// CONFIGURACIÓN DE FECHA Y HORA
// ============================================

define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i');
define('TIMEZONE', 'America/La_Paz');

// Establecer zona horaria
date_default_timezone_set(TIMEZONE);

// ============================================
// CONFIGURACIÓN DE ERRORES
// ============================================

if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ============================================
// RUTAS URL
// ============================================

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
$baseUrl = $protocol . '://' . $host;

define('BASE_URL', $baseUrl);

// ============================================
// MENSAJES DEL SISTEMA
// ============================================

define('MESSAGES', [
    'login_success' => 'Bienvenido al sistema',
    'login_error' => 'Credenciales incorrectas',
    'logout_success' => 'Sesión cerrada correctamente',
    'save_success' => 'Datos guardados correctamente',
    'save_error' => 'Error al guardar los datos',
    'delete_success' => 'Eliminado correctamente',
    'delete_error' => 'Error al eliminar',
    'access_denied' => 'Acceso denegado',
    'session_expired' => 'Tu sesión ha expirado'
]);

// ============================================
// UTILIDADES
// ============================================

/**
 * Redirigir a una URL
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Obtener URL base
 */
function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Mostrar mensaje flash
 */
function flash($key, $message = null) {
    if ($message === null) {
        return $_SESSION['flash'][$key] ?? null;
    }
    $_SESSION['flash'][$key] = $message;
}

/**
 * Sanitizar salida HTML
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format fecha
 */
function format_date($date, $format = DATE_FORMAT) {
    if (empty($date)) return '-';
    return date($format, strtotime($date));
}
