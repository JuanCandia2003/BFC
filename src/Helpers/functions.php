<?php
/**
 * Funciones Helpers - Utilidades globales para la aplicación
 * Estas funciones complementan las definidas en config/config.php
 */

function asset($path = '') {
    return base_url($path);
}

function auth_check() {
    return isset($_SESSION['user_id']);
}

function auth_user() {
    return $_SESSION['user'] ?? null;
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function is_bailarin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'bailarin';
}
