<?php
/**
 * Clase Auth - Manejo de Autenticación
 * 
 * Gestiona el inicio de sesión, cierre de sesión
 * y verificación de usuarios.
 */

class Auth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Iniciar sesión de usuario
     */
    public function login($identifier, $password) {
        // Intentar con admin
        $user = $this->checkAdmin($identifier, $password);
        if ($user) {
            return $this->createSession($user, 'admin');
        }

        // Intentar con bailarín
        $user = $this->checkBailarin($identifier, $password);
        if ($user) {
            return $this->createSession($user, 'bailarin');
        }

        return false;
    }

    /**
     * Verificar credenciales de administrador
     */
    private function checkAdmin($identifier, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE usuario = ?");
        $stmt->execute([$identifier]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            return $admin;
        }

        return false;
    }

    /**
     * Verificar credenciales de bailarín
     */
    private function checkBailarin($identifier, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM bailarines WHERE email = ? AND activo = 1");
        $stmt->execute([$identifier]);
        $bailarin = $stmt->fetch();

        if ($bailarin && password_verify($password, $bailarin['password'])) {
            return $bailarin;
        }

        return false;
    }

    /**
     * Crear sesión de usuario
     */
    private function createSession($user, $role) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $user['nombre'];
        $_SESSION['login_time'] = time();
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';

        return true;
    }

    /**
     * Cerrar sesión
     */
    public function logout() {
        // Limpiar todas las variables de sesión
        $_SESSION = [];

        // Destruir la cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Destruir la sesión
        session_destroy();

        return true;
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function isAdmin() {
        return self::isLoggedIn() && $_SESSION['role'] === 'admin';
    }

    /**
     * Verificar si el usuario es bailarín
     */
    public static function isBailarin() {
        return self::isLoggedIn() && $_SESSION['role'] === 'bailarin';
    }

    /**
     * Obtener el usuario actual
     */
    public static function user() {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'name' => $_SESSION['name'] ?? null
        ];
    }

    /**
     * Verificar y renovar sesión
     */
    public static function checkSession() {
        if (!self::isLoggedIn()) {
            return false;
        }

        // Verificar tiempo de sesión
        $sessionLifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 3600;
        
        if (isset($_SESSION['login_time'])) {
            $elapsed = time() - $_SESSION['login_time'];
            
            if ($elapsed > $sessionLifetime) {
                self::logout();
                return false;
            }
            
            // Renovar tiempo de sesión
            $_SESSION['login_time'] = time();
        }

        return true;
    }

    /**
     * Generar hash de contraseña
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verificar contraseña
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Restablecer contraseña de bailarín
     */
    public function resetBailarinPassword($bailarinId) {
        $newPassword = 'bailarin123';
        $hash = self::hashPassword($newPassword);

        $stmt = $this->pdo->prepare("UPDATE bailarines SET password = ? WHERE id = ?");
        return $stmt->execute([$hash, $bailarinId]);
    }

    /**
     * Proteger acceso a páginas
     */
    public static function requireLogin($redirectTo = 'login.php') {
        if (!self::checkSession()) {
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Proteger acceso solo para admin
     */
    public static function requireAdmin($redirectTo = 'login.php') {
        self::requireLogin($redirectTo);
        
        if (!self::isAdmin()) {
            header("Location: $redirectTo");
            exit;
        }
    }

    /**
     * Proteger acceso solo para bailarines
     */
    public static function requireBailarin($redirectTo = 'login.php') {
        self::requireLogin($redirectTo);
        
        if (!self::isBailarin()) {
            header("Location: $redirectTo");
            exit;
        }
    }
}
