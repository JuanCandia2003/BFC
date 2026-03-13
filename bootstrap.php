<?php
/**
 * Bootstrap - Inicialización de la aplicación BFC
 * 
 * Punto de entrada de la aplicación.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_START', microtime(true));

if (php_sapi_name() === 'cli' || !isset($_SERVER['SCRIPT_FILENAME'])) {
    $basePath = __DIR__;
} else {
    $scriptPath = $_SERVER['SCRIPT_FILENAME'];
    
    // Si el archivo está en public/, subir un nivel
    if (strpos($scriptPath, '/public/') !== false || strpos($scriptPath, '\\public\\') !== false) {
        $basePath = dirname(dirname($scriptPath));
    } else {
        $basePath = dirname($scriptPath);
    }
}

define('BOOTSTRAP_BASE_PATH', $basePath);

require_once $basePath . '/config/config.php';
require_once $basePath . '/src/Database/Connection.php';
require_once $basePath . '/src/Helpers/functions.php';

use Src\Database\Connection;

try {
    $pdo = Connection::getInstance()->getConnection();
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
}
