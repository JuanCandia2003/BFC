<?php
require 'includes/db.php';

echo "<h2>Diagnóstico y Reparación de Bailarines</h2>";

$email = 'juan@bfc.com';
$password_raw = 'bailarin123';
$password_hash = password_hash($password_raw, PASSWORD_DEFAULT);
$nombre = 'Juan Perez';

try {
    // 1. Ver qué hay en la tabla actualmente
    echo "<h3>Usuarios actuales en tabla 'bailarines':</h3>";
    $stmt = $pdo->query("SELECT id, nombre, email, activo FROM bailarines");
    $users = $stmt->fetchAll();
    
    if (count($users) == 0) {
        echo "No hay bailarines registrados.<br>";
    } else {
        echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Activo</th></tr>";
        foreach ($users as $u) {
            echo "<tr><td>{$u['id']}</td><td>{$u['nombre']}</td><td>{$u['email']}</td><td>{$u['activo']}</td></tr>";
        }
        echo "</table><br>";
    }

    // 2. Insertar o Actualizar usuario de prueba
    echo "<h3>Creando/Actualizando usuario de prueba...</h3>";
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM bailarines WHERE email = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Update password and ensure active
        $stmt = $pdo->prepare("UPDATE bailarines SET password = ?, activo = 1 WHERE email = ?");
        $stmt->execute([$password_hash, $email]);
        echo "✅ Usuario actualizado: <strong>$email</strong> (Password reset, Activo=1)<br>";
    } else {
        // Create new
        $stmt = $pdo->prepare("INSERT INTO bailarines (nombre, email, telefono, genero, password, activo) VALUES (?, ?, '555-1234', 'F', ?, 1)");
        $stmt->execute([$nombre, $email, $password_hash]);
        echo "✅ Usuario creado: <strong>$email</strong><br>";
    }

    echo "<br>Credenciales para probar:<br>";
    echo "Email: <strong>$email</strong><br>";
    echo "Password: <strong>$password_raw</strong><br>";
    echo "<br><a href='login.php'>Ir al Login</a>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
