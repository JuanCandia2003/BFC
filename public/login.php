<?php
session_start();
require __DIR__ . '/../includes/db.php';

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

    // Check Bailarines Table
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e293b;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --font-heading: 'Playfair Display', serif;
            --font-body: 'Inter', sans-serif;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.85)), 
                        url('assets/images/hero1.jpg');
            background-size: cover;
            background-position: center;
            padding: 2rem;
        }

        .login-card {
            background: #fff;
            width: 100%;
            max-width: 420px;
            padding: 3rem 2.5rem;
            border-radius: 4px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-logo h1 {
            font-family: var(--font-heading);
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text-main);
            letter-spacing: 0.5px;
        }

        .login-logo p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        .login-title {
            font-family: var(--font-heading);
            font-size: 1.35rem;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.95rem;
            font-family: var(--font-body);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--text-main);
            box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.1);
        }

        .form-group input::placeholder {
            color: #94a3b8;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.9rem;
            padding: 0;
        }

        .password-toggle:hover {
            color: var(--text-main);
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background: var(--text-main);
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: #0f172a;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }

        .login-footer a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease;
        }

        .login-footer a:hover {
            color: var(--text-main);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <h1>Ballet Folclórico<br>de Cochabamba</h1>
            <p>Sistema de Gestión</p>
        </div>

        <h2 class="login-title">Iniciar Sesión</h2>
        
        <?php if ($error): ?>
            <div class="login-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email_user">Usuario o Email</label>
                <input type="text" id="email_user" name="email_user" required placeholder="admin o correo@bfc.com" autocomplete="username">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">Mostrar</button>
                </div>
            </div>
            
            <button type="submit" class="btn-submit">Ingresar</button>
        </form>
        
        <div class="login-footer">
            <a href="index.php">← Volver al inicio</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const btn = document.querySelector('.password-toggle');
            if (input.type === 'password') {
                input.type = 'text';
                btn.textContent = 'Ocultar';
            } else {
                input.type = 'password';
                btn.textContent = 'Mostrar';
            }
        }
    </script>
</body>
</html>
