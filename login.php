<?php
session_start();
require 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_user = trim($_POST['email_user']);
    $password = $_POST['password'];

    // Check Admin Table
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE usuario = ?");
    $stmt->execute([$email_user]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['role'] = 'admin';
        $_SESSION['name'] = $admin['nombre'];
        header("Location: admin.php");
        exit;
    }

    // Check Bailarines Table (using email)
    $stmt = $pdo->prepare("SELECT * FROM bailarines WHERE email = ? AND activo = 1");
    $stmt->execute([$email_user]);
    $bailarin = $stmt->fetch();

    if ($bailarin && password_verify($password, $bailarin['password'])) {
        $_SESSION['user_id'] = $bailarin['id'];
        $_SESSION['role'] = 'bailarin';
        $_SESSION['name'] = $bailarin['nombre'];
        header("Location: bailarin.php");
        exit;
    }

    $error = "Credenciales incorrectas o cuenta inactiva.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - BFC</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1 class="logo"><a href="index.php">BFC</a></h1>
        </div>
    </header>

    <main class="section-padding">
        <div class="form-container">
            <h2 class="section-title" style="font-size: 1.5rem; margin-bottom: 2rem;">Iniciar Sesión</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email_user">Usuario o Email</label>
                    <input type="text" id="email_user" name="email_user" required placeholder="admin o email@bfc.com">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Ingresar</button>
            </form>
            <p style="margin-top: 1rem; text-align: center;"><a href="index.php">Volver al inicio</a></p>
        </div>
    </main>
</body>
</html>
