<?php
session_start();
require 'includes/db.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bailarin') {
    header("Location: login.php");
    exit;
}

$bailarin_id = $_SESSION['user_id'];
$mensaje = '';

// Handle Loan Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar_prestamo'])) {
    $vestuario_id = $_POST['vestuario_id'];
    $funcion_id = $_POST['funcion_id'];
    
    // Validar disponibilidad (simple check)
    $stmt = $pdo->prepare("SELECT cantidad_disponible FROM vestuarios WHERE id = ?");
    $stmt->execute([$vestuario_id]);
    $vestuario = $stmt->fetch();
    
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($vestuario && $vestuario['cantidad_disponible'] > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO prestamos (bailarin_id, vestuario_id, funcion_id, estado, observaciones) VALUES (?, ?, ?, 'pendiente', ?)");
            $stmt->execute([$bailarin_id, $vestuario_id, $funcion_id, $observaciones]);
            $mensaje = "<div class='alert alert-success'>Solicitud enviada con éxito. Pendiente de aprobación.</div>";
        } catch (Exception $e) {
            $mensaje = "<div class='alert alert-danger'>Error al solicitar: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensaje = "<div class='alert alert-danger'>Este vestuario no está disponible por el momento.</div>";
    }
}

// Fetch Data
$query = "SELECT * FROM vestuarios WHERE cantidad_disponible > 0";
$params = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query .= " AND nombre LIKE ?";
    $params[] = "%" . $_GET['q'] . "%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vestuarios = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM funciones WHERE fecha >= CURDATE() ORDER BY fecha ASC");
$funciones = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT p.*, v.nombre as vestuario, f.nombre as funcion 
                       FROM prestamos p 
                       JOIN vestuarios v ON p.vestuario_id = v.id 
                       JOIN funciones f ON p.funcion_id = f.id 
                       WHERE p.bailarin_id = ? 
                       ORDER BY p.fecha_solicitud DESC");
$stmt->execute([$bailarin_id]);
$mis_prestamos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Bailarín - BFC</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        .card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }
        .costume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .costume-card {
            border: 1px solid #eee;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s;
        }
        .costume-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .costume-img {
            height: 200px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .costume-info {
            padding: 1rem;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-pendiente { background: #ffeeba; color: #856404; }
        .status-aprobado { background: #d4edda; color: #155724; }
        .status-rechazado { background: #f8d7da; color: #721c24; }
        
        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header class="main-header" style="position: relative;">
        <div class="container">
            <h1 class="logo">BFC - Bailarines</h1>
            <nav class="main-nav">
                <ul>
                    <li>Hola, <?php echo htmlspecialchars($_SESSION['name']); ?></li>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-grid">
        <!-- Sección Izquierda: Catálogo -->
        <div class="main-content">
            <?php echo $mensaje; ?>
            
            <div class="card">
                <h2>Catálogo de Vestuario</h2>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <p style="margin: 0;">Selecciona un vestuario para solicitar préstamo.</p>
                    <form method="GET" style="display: flex; gap: 5px;">
                        <input type="text" name="q" placeholder="Buscar vestuario..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
                        <button type="submit" class="btn-submit" style="padding: 5px 10px;">Buscar</button>
                        <?php if(isset($_GET['q'])): ?>
                            <a href="bailarin.php" style="padding: 5px 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px;">Limpiar</a>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="costume-grid">
                    <?php foreach ($vestuarios as $v): ?>
                        <div class="costume-card">
                            <div class="costume-img">
                                <?php if ($v['imagen']): ?>
                                    <img src="assets/images/<?php echo htmlspecialchars($v['imagen']); ?>" alt="<?php echo htmlspecialchars($v['nombre']); ?>">
                                <?php else: ?>
                                    <span>Sin Foto</span>
                                <?php endif; ?>
                            </div>
                            <div class="costume-info">
                                <h3><?php echo htmlspecialchars($v['nombre']); ?></h3>
                                <p>Talla: <?php echo htmlspecialchars($v['talla']); ?></p>
                                <p>Disp: <?php echo $v['cantidad_disponible']; ?></p>
                                
                                <form method="POST" style="margin-top: 10px;">
                                    <input type="hidden" name="vestuario_id" value="<?php echo $v['id']; ?>">
                                    <select name="funcion_id" required style="width: 100%; padding: 5px; margin-bottom: 5px;">
                                        <option value="">Seleccionar Función</option>
                                        <?php foreach ($funciones as $f): ?>
                                            <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <textarea name="observaciones" placeholder="Observaciones (opcional)" style="width: 100%; padding: 5px; margin-bottom: 5px; resize: vertical;" rows="2"></textarea>
                                    <button type="submit" name="solicitar_prestamo" class="btn-submit" style="padding: 0.5rem;">Solicitar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Sección Derecha: Mis Préstamos -->
        <div class="sidebar">
            <div class="card">
                <h2>Mis Solicitudes</h2>
                <?php if (empty($mis_prestamos)): ?>
                    <p>No tienes solicitudes activas.</p>
                <?php else: ?>
                    <ul style="list-style: none;">
                        <?php foreach ($mis_prestamos as $p): ?>
                            <li style="border-bottom: 1px solid #eee; padding: 10px 0;">
                                <strong><?php echo htmlspecialchars($p['vestuario']); ?></strong><br>
                                <small>Función: <?php echo htmlspecialchars($p['funcion']); ?></small><br>
                                <span class="status-badge status-<?php echo $p['estado']; ?>">
                                    <?php echo ucfirst($p['estado']); ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>
