<?php
require 'includes/db.php';

echo "<h2>Restableciendo Contraseña de Admin...</h2>";

$usuario = 'admin';
$password_raw = 'admin123';
$password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
$nombre = 'Administrador Principal';

try {
    // Verificar si el usuario existe
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Actualizar contraseña
        $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE usuario = ?");
        $stmt->execute([$password_hash, $usuario]);
        echo "✅ Contraseña actualizada correctamente para el usuario: <strong>$usuario</strong><br>";
    } else {
        // Crear usuario si no existe
        $stmt = $pdo->prepare("INSERT INTO admin (usuario, password, nombre) VALUES (?, ?, ?)");
        $stmt->execute([$usuario, $password_hash, $nombre]);
        echo "✅ Usuario creado correctamente: <strong>$usuario</strong><br>";
    }
    
    echo "<br>Nueva Contraseña: <strong>$password_raw</strong><br>";
    echo "<br><a href='login.php'>Ir al Login</a>";

} catch (PDOException $e) {
    echo "❌ Error de Base de Datos: " . $e->getMessage();
}
?>
