<?php
session_start();
require __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bailarin') {
    header("Location: login.php");
    exit;
}

$bailarin_id = $_SESSION['user_id'];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitar_prestamo'])) {
    $vestuario_id = $_POST['vestuario_id'];
    $funcion_id = $_POST['funcion_id'];
    
    $stmt = $pdo->prepare("SELECT cantidad_disponible FROM vestuarios WHERE id = ?");
    $stmt->execute([$vestuario_id]);
    $vestuario = $stmt->fetch();
    
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : '';

    if ($vestuario && $vestuario['cantidad_disponible'] > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO prestamos (bailarin_id, vestuario_id, funcion_id, estado, observaciones) VALUES (?, ?, ?, 'pendiente', ?)");
            $stmt->execute([$bailarin_id, $vestuario_id, $funcion_id, $observaciones]);
            $mensaje = "<script>showToast('Solicitud enviada con éxito. Pendiente de aprobación.', 'success');</script>";
        } catch (Exception $e) {
            $mensaje = "<script>showToast('Error al solicitar préstamo.', 'error');</script>";
        }
    } else {
        $mensaje = "<script>showToast('Este vestuario no está disponible.', 'error');</script>";
    }
}

// Fetch Data
$query = "SELECT * FROM vestuarios WHERE cantidad_disponible > 0";
$params = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query .= " AND nombre LIKE ?";
    $params[] = "%" . $_GET['q'] . "%";
}

if (isset($_GET['genero']) && !empty($_GET['genero']) && $_GET['genero'] != 'all') {
    $query .= " AND genero = ?";
    $params[] = $_GET['genero'];
}

if (isset($_GET['talla']) && !empty($_GET['talla'])) {
    $query .= " AND talla LIKE ?";
    $params[] = "%" . $_GET['talla'] . "%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vestuarios = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM funciones WHERE fecha >= CURDATE() ORDER BY fecha ASC");
$funciones = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT p.*, v.nombre as vestuario, v.imagen as v_imagen, f.nombre as funcion, f.fecha as f_fecha 
                       FROM prestamos p 
                       JOIN vestuarios v ON p.vestuario_id = v.id 
                       JOIN funciones f ON p.funcion_id = f.id 
                       WHERE p.bailarin_id = ? 
                       ORDER BY p.fecha_solicitud DESC");
$stmt->execute([$bailarin_id]);
$mis_prestamos = $stmt->fetchAll();

$nombre_bailarin = $_SESSION['name'];
$inicial = strtoupper(substr($nombre_bailarin, 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Bailarín - BFC</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: #1e293b;
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --danger-color: #ef4444;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
            --badge-pending: #fef3c7;
            --badge-pending-text: #92400e;
            --badge-approved: #d1fae5;
            --badge-approved-text: #065f46;
            --badge-returned: #dbeafe;
            --badge-returned-text: #1e40af;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            margin: 0;
            color: var(--text-main);
        }
        
        /* Header */
        .main-header {
            background: var(--sidebar-bg);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .logo {
            color: white;
            font-size: 1.5rem;
            margin: 0;
            font-weight: 700;
        }
        .logo span { color: var(--accent-color); }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            cursor: pointer;
            position: relative;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1rem;
        }
        .user-name {
            font-weight: 500;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            min-width: 150px;
            margin-top: 0.5rem;
            overflow: hidden;
        }
        .dropdown-menu.show { display: block; }
        .dropdown-menu a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-main);
            text-decoration: none;
            transition: background 0.2s;
        }
        .dropdown-menu a:hover { background: var(--primary-bg); }
        .dropdown-menu a.danger { color: var(--danger-color); }

        /* Main Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        @media (max-width: 1024px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        /* Cards */
        .card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-main);
        }

        /* Filters */
        .filter-bar {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: var(--primary-bg);
            border-radius: 0.5rem;
        }
        .filter-bar input,
        .filter-bar select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: white;
        }
        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        /* Costume Grid */
        .costume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
        }
        .costume-card {
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.2s;
            background: white;
        }
        .costume-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .costume-img {
            height: 180px;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .costume-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .costume-img svg {
            width: 48px;
            height: 48px;
            color: #94a3b8;
        }
        .costume-info {
            padding: 1.25rem;
        }
        .costume-name {
            font-weight: 600;
            font-size: 1rem;
            margin: 0 0 0.5rem;
            color: var(--text-main);
        }
        .costume-details {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .costume-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            background: var(--primary-bg);
            color: var(--text-muted);
        }
        .costume-stock {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        .costume-stock span {
            font-weight: 600;
            color: var(--success-color);
        }

        /* Request Form */
        .request-form select,
        .request-form textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
            font-family: inherit;
        }
        .request-form select:focus,
        .request-form textarea:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        .btn-request {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent-color);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-request:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        /* Sidebar - My Requests */
        .requests-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 600px;
            overflow-y: auto;
        }
        .request-item {
            padding: 1rem;
            background: var(--primary-bg);
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
        }
        .request-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        .request-vestuario {
            font-weight: 600;
            color: var(--text-main);
        }
        .request-funcion {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .badge-pending { background: var(--badge-pending); color: var(--badge-pending-text); }
        .badge-approved { background: var(--badge-approved); color: var(--badge-approved-text); }
        .badge-returned { background: var(--badge-returned); color: var(--badge-returned-text); }
        
        .request-details {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .request-date {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 0.5rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }
        .empty-state svg {
            width: 64px;
            height: 64px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0 0 1.5rem;
        }
        .modal-summary {
            background: var(--primary-bg);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .modal-summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .modal-summary-row:last-child { border-bottom: none; }
        .modal-summary-label { color: var(--text-muted); font-size: 0.875rem; }
        .modal-summary-value { font-weight: 500; }
        .modal-actions {
            display: flex;
            gap: 0.75rem;
        }
        .modal-actions button,
        .modal-actions a {
            flex: 1;
            padding: 0.75rem;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-confirm {
            background: var(--accent-color);
            color: white;
            border: none;
        }
        .btn-confirm:hover { background: var(--accent-hover); }
        .btn-cancel {
            background: var(--primary-bg);
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }
        .btn-cancel:hover { background: var(--border-color); }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 2000;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .toast {
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            color: white;
            font-weight: 500;
            animation: slideIn 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .toast-success { background: var(--success-color); }
        .toast-error { background: var(--danger-color); }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .dashboard-grid { padding: 0 1rem; }
            .main-header { padding: 1rem; }
            .user-name { display: none; }
            .costume-grid { grid-template-columns: 1fr 1fr; }
            .costume-img { height: 140px; }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <h1 class="logo">BFC <span>Panel</span></h1>
        <div class="user-menu">
            <div class="user-info" onclick="toggleDropdown()">
                <div class="user-avatar"><?php echo $inicial; ?></div>
                <span class="user-name"><?php echo htmlspecialchars($nombre_bailarin); ?></span>
                <svg style="width:16px;height:16px;color:white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
            <div class="dropdown-menu" id="userDropdown">
                <a href="#" onclick="confirmLogout()">Cerrar Sesión</a>
            </div>
        </div>
    </header>

    <?php echo $mensaje; ?>

    <div class="dashboard-grid">
        <!-- Main Content - Catalog -->
        <div class="main-content">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Catálogo de Vestuario</h2>
                </div>

                <!-- Filters -->
                <form method="GET" class="filter-bar">
                    <input type="text" name="q" placeholder="Buscar vestuario..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" style="flex: 1; min-width: 150px;">
                    <select name="genero">
                        <option value="all">Todos los géneros</option>
                        <option value="Hombre" <?php echo isset($_GET['genero']) && $_GET['genero'] == 'Hombre' ? 'selected' : ''; ?>>Hombre</option>
                        <option value="Mujer" <?php echo isset($_GET['genero']) && $_GET['genero'] == 'Mujer' ? 'selected' : ''; ?>>Mujer</option>
                        <option value="Unisex" <?php echo isset($_GET['genero']) && $_GET['genero'] == 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
                    </select>
                    <button type="submit" class="btn-request" style="width: auto; padding: 0.5rem 1.5rem;">Buscar</button>
                    <?php if(isset($_GET['q']) || (isset($_GET['genero']) && $_GET['genero'] != 'all')): ?>
                    <a href="bailarin.php" class="btn-request" style="width: auto; padding: 0.5rem 1.5rem; background: #6b7280; text-decoration: none;">Limpiar</a>
                    <?php endif; ?>
                </form>

                <!-- Costume Grid -->
                <?php if (empty($vestuarios)): ?>
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        <p>No hay vestuarios disponibles</p>
                    </div>
                <?php else: ?>
                    <div class="costume-grid">
                        <?php foreach ($vestuarios as $v): ?>
                            <div class="costume-card">
                                <div class="costume-img">
                                    <?php if ($v['imagen']): ?>
                                        <img src="assets/images/<?php echo htmlspecialchars($v['imagen']); ?>" alt="<?php echo htmlspecialchars($v['nombre']); ?>">
                                    <?php else: ?>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="costume-info">
                                    <h3 class="costume-name"><?php echo htmlspecialchars($v['nombre']); ?></h3>
                                    <div class="costume-details">
                                        <span class="costume-badge"><?php echo htmlspecialchars($v['talla']); ?></span>
                                        <span class="costume-badge"><?php echo $v['genero']; ?></span>
                                    </div>
                                    <div class="costume-stock">
                                        Disponibles: <span><?php echo $v['cantidad_disponible']; ?></span> / <?php echo $v['cantidad_total']; ?>
                                    </div>
                                    
                                    <button type="button" class="btn-request" onclick="openRequestModal(<?php echo $v['id']; ?>, '<?php echo htmlspecialchars($v['nombre']); ?>', <?php echo $v['cantidad_disponible']; ?>)">
                                        Solicitar Préstamo
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar - My Requests -->
        <div class="sidebar">
            <div class="card">
                <h2 class="card-title" style="margin-bottom: 1.5rem;">Mis Solicitudes</h2>
                
                <?php if (empty($mis_prestamos)): ?>
                    <div class="empty-state" style="padding: 2rem;">
                        <p>No tienes solicitudes aún</p>
                    </div>
                <?php else: ?>
                    <div class="requests-list">
                        <?php foreach ($mis_prestamos as $p): ?>
                            <div class="request-item">
                                <div class="request-item-header">
                                    <div>
                                        <div class="request-vestuario"><?php echo htmlspecialchars($p['vestuario']); ?></div>
                                        <div class="request-funcion"><?php echo htmlspecialchars($p['funcion']); ?></div>
                                    </div>
                                    <span class="badge badge-<?php echo $p['estado']; ?>"><?php echo ucfirst($p['estado']); ?></span>
                                </div>
                                <?php if($p['observaciones']): ?>
                                <div class="request-details">
                                    <strong>Obs:</strong> <?php echo htmlspecialchars($p['observaciones']); ?>
                                </div>
                                <?php endif; ?>
                                <div class="request-date">
                                    Solicitado: <?php echo date('d/m/Y H:i', strtotime($p['fecha_solicitud'])); ?>
                                    <?php if($p['fecha_devolucion']): ?>
                                    <br>Devuelto: <?php echo date('d/m/Y', strtotime($p['fecha_devolucion'])); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Request Modal -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <h3 class="modal-title">Confirmar Solicitud</h3>
            <div class="modal-summary">
                <div class="modal-summary-row">
                    <span class="modal-summary-label">Vestuario</span>
                    <span class="modal-summary-value" id="modalVestuario"></span>
                </div>
                <div class="modal-summary-row">
                    <span class="modal-summary-label">Disponibles</span>
                    <span class="modal-summary-value" id="modalStock"></span>
                </div>
                <div class="modal-summary-row">
                    <span class="modal-summary-label">Función</span>
                    <span class="modal-summary-value" id="modalFuncion"></span>
                </div>
                <div class="modal-summary-row">
                    <span class="modal-summary-label">Observaciones</span>
                    <span class="modal-summary-value" id="modalObservaciones">-</span>
                </div>
            </div>
            <form method="POST" id="modalForm">
                <input type="hidden" name="vestuario_id" id="modalVestuarioId">
                <div class="request-form" style="margin-bottom: 1rem;">
                    <select name="funcion_id" id="modalFuncionSelect" required>
                        <option value="">Seleccionar Función *</option>
                        <?php foreach ($funciones as $f): ?>
                            <option value="<?php echo $f['id']; ?>"><?php echo htmlspecialchars($f['nombre']); ?> (<?php echo date('d/m/Y', strtotime($f['fecha'])); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <textarea name="observaciones" id="modalObservacionesInput" placeholder="Observaciones (opcional)" rows="2"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" name="solicitar_prestamo" class="btn-confirm">Confirmar Solicitud</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content" style="max-width: 350px; text-align: center;">
            <h3 class="modal-title">¿Cerrar Sesión?</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem;">¿Estás seguro de que deseas cerrar tu sesión?</p>
            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeLogoutModal()">Cancelar</button>
                <a href="logout.php" class="btn-confirm" style="display: block; padding: 0.75rem; border-radius: 0.5rem;">Sí, Cerrar</a>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script>
        // Dropdown
        function toggleDropdown() {
            document.getElementById('userDropdown').classList.toggle('show');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-info')) {
                document.getElementById('userDropdown').classList.remove('show');
            }
        });

        // Request Modal
        function openRequestModal(id, nombre, stock) {
            document.getElementById('modalVestuario').textContent = nombre;
            document.getElementById('modalStock').textContent = stock;
            document.getElementById('modalVestuarioId').value = id;
            document.getElementById('modalObservaciones').textContent = document.getElementById('modalObservacionesInput').value || '-';
            document.getElementById('requestModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('requestModal').classList.remove('active');
        }

        document.getElementById('modalObservacionesInput').addEventListener('input', function() {
            document.getElementById('modalObservaciones').textContent = this.value || '-';
        });

        // Logout Modal
        function confirmLogout() {
            document.getElementById('logoutModal').classList.add('active');
            document.getElementById('userDropdown').classList.remove('show');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        // Close modals on backdrop click
        document.getElementById('requestModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
        });

        // Toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</body>
</html>
