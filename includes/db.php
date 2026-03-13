<?php
/**
 * Legacy compatibility - Redirige al bootstrap
 * @deprecated Usar bootstrap.php en su lugar
 */

$scriptDir = $_SERVER['SCRIPT_FILENAME'] ?? __DIR__;

// Si el archivo está en public/, subir un nivel
if (strpos($scriptDir, '/public/') !== false || strpos($scriptDir, '\\public\\') !== false) {
    $basePath = dirname(dirname($scriptDir));
} else {
    $basePath = dirname($scriptDir);
}

require $basePath . '/bootstrap.php';
