<?php
session_start();
require 'includes/db.php';

// Auth Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
$message = '';

// Helper function to handle image upload
function uploadImage($file) {
    $target_dir = "assets/images/";
    $target_file = $target_dir . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $target_file);
    return basename($file["name"]);
}

// Router Logic
switch($view) {
    case 'bailarines':
        require 'includes/admin/bailarines.php';
        break;
    case 'vestuarios':
        require 'includes/admin/vestuarios.php';
        break;
    case 'funciones':
        require 'includes/admin/funciones.php';
        break;
    case 'prestamos':
        require 'includes/admin/prestamos.php';
        break;
    default:
        // Dashboard Stats
        $stmt = $pdo->query("SELECT COUNT(*) FROM bailarines");
        $total_bailarines = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM vestuarios");
        $total_vestuarios = $stmt->fetchColumn();
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'pendiente'");
        $pending_loans = $stmt->fetchColumn();
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin - BFC</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-bg: #f8fafc;
            --sidebar-bg: #1e293b;
            --sidebar-text: #e2e8f0;
            --sidebar-hover: #334155;
            --accent-color: #3b82f6; /* Blue-500 */
            --danger-color: #ef4444; /* Red-500 */
            --success-color: #10b981; /* Emerald-500 */
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #334155;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            margin: 0;
            color: var(--text-main);
        }
        .admin-layout {
            display: flex;
            flex-direction: column; /* Stack header and content */
            min-height: 100vh;
            width: 100%;
        }
        /* Header/Sidebar Styling */
        .admin-sidebar {
            width: 100%; /* Full width */
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 1rem 2rem; /* Reduced vertical padding */
            display: flex;
            flex-direction: row; /* Horizontal alignment */
            align-items: center;
            justify-content: space-between; /* Logo left, Nav right */
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); /* Shadow below */
            z-index: 20;
            position: sticky;
            top: 0;
        }
        .admin-sidebar h2 {
            text-align: left;
            margin-bottom: 0; /* Remove bottom margin */
            font-size: 1.25rem;
            margin-right: 2rem;
        }
        .admin-sidebar nav {
            display: flex;
            flex-direction: row; /* Horizontal links */
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping on small screens */
            transition: all 0.3s ease;
        }
        
        /* Hamburger Menu */
        .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            z-index: 30;
        }
        .menu-toggle span {
            width: 25px;
            height: 3px;
            background-color: #fff;
            border-radius: 2px;
            transition: 0.3s;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                padding: 1rem;
                flex-wrap: wrap;
            }
            .menu-toggle {
                display: flex;
            }
            .admin-sidebar nav {
                display: none; /* Hide by default on mobile */
                width: 100%;
                flex-direction: column;
                padding-top: 1rem;
                gap: 0.5rem;
            }
            .admin-sidebar nav.active {
                display: flex;
            }
            .admin-sidebar a {
                width: 100%;
            }
            .logout-link {
                margin-left: 0;
            }
            .admin-content {
                padding: 1.5rem;
            }
        }
        .admin-sidebar a {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .admin-sidebar a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateY(-2px); /* Up instead of right */
        }
        .admin-sidebar a.active {
            background: var(--accent-color);
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
        }
        .logout-link {
            margin-top: 0 !important; /* Reset auto margin */
            margin-left: 1rem;
        }

        /* Content Styling */
        .admin-content {
            flex-grow: 1;
            padding: 2.5rem 3rem;
            overflow-y: auto;
            max-width: 1600px; /* Prevent over-stretch on ultra-wide */
        }
        
        h1, h2, h3 {
            color: #1e293b; /* Slate-800 */
            margin-top: 0;
        }
        h1 { font-size: 2rem; margin-bottom: 2rem; }
        
        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .stat-card h3 {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b; /* Slate-500 */
            margin-bottom: 0.5rem;
        }
        .stat-card p {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        /* Tables & Lists */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--card-bg);
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 1.5rem;
        }
        thead {
            background-color: #f1f5f9; /* Slate-100 */
        }
        th {
            padding: 1rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            border-bottom: 1px solid var(--border-color);
        }
        td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: #334155;
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; }

        /* Buttons */
        .btn-submit, .action-btn {
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-submit {
            background-color: var(--accent-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
        }
        .btn-submit:hover {
            background-color: #2563eb; /* Blue-600 */
            transform: translateY(-1px);
        }
        .action-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            margin-right: 0.5rem;
        }
        .action-btn:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-edit { background-color: #0ea5e9; /* Sky-500 */ }
        .btn-delete { background-color: var(--danger-color); }

        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <aside class="admin-sidebar">
            <h2>BFC Panel</h2>
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav id="adminNav">
                <a href="?view=dashboard" class="<?php echo $view == 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
                <a href="?view=bailarines" class="<?php echo $view == 'bailarines' ? 'active' : ''; ?>">Bailarines</a>
                <a href="?view=vestuarios" class="<?php echo $view == 'vestuarios' ? 'active' : ''; ?>">Vestuario</a>
                <a href="?view=funciones" class="<?php echo $view == 'funciones' ? 'active' : ''; ?>">Funciones</a>
                <a href="?view=prestamos" class="<?php echo $view == 'prestamos' ? 'active' : ''; ?>">Préstamos</a>
                <a href="logout.php" class="logout-link">Cerrar Sesión</a>
            </nav>
    </aside>
    <div class="admin-layout">
        <main class="admin-content">
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php if ($view == 'dashboard'): ?>
                <h1>Resumen General</h1>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Bailarines Registrados</h3>
                        <p><?php echo $total_bailarines; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Vestuarios</h3>
                        <p><?php echo $total_vestuarios; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Solicitudes Pendientes</h3>
                        <p style="color: var(--accent-color);"><?php echo $pending_loans; ?></p>
                        <a href="?view=prestamos" style="display: block; margin-top: 1rem; color: var(--accent-color); font-size: 0.875rem; font-weight: 600;">Gestionar Solicitudes &rarr;</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- The included files will render their content here -->
        </main>
        
    </div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const adminNav = document.getElementById('adminNav');

        menuToggle.addEventListener('click', () => {
            adminNav.classList.toggle('active');
        });
    </script>
</body>
</html>
