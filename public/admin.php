<?php
require __DIR__ . '/../bootstrap.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect('login.php');
}

$view = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
$message = '';

// Get pending loans count (used in header badge)
$stmt = $pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'pendiente'");
$pending_loans = $stmt->fetchColumn();

// Get stats for dashboard
$stmt = $pdo->query("SELECT COUNT(*) FROM bailarines");
$total_bailarines = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM vestuarios");
$total_vestuarios = $stmt->fetchColumn();

// Get recent loans for dashboard
$recent_loans = $pdo->query("SELECT p.*, b.nombre as bailarin, v.nombre as vestuario 
    FROM prestamos p 
    JOIN bailarines b ON p.bailarin_id = b.id 
    JOIN vestuarios v ON p.vestuario_id = v.id 
    ORDER BY p.fecha_solicitud DESC LIMIT 5")->fetchAll();

// Capture content from includes using output buffering
ob_start();

switch($view) {
    case 'bailarines':
        require SRC_PATH . '/Views/Admin/bailarines.php';
        break;
    case 'vestuarios':
        require SRC_PATH . '/Views/Admin/vestuarios.php';
        break;
    case 'funciones':
        require SRC_PATH . '/Views/Admin/funciones.php';
        break;
    case 'prestamos':
        require SRC_PATH . '/Views/Admin/prestamos.php';
        break;
    case 'dashboard':
        ?>
        <h1>Resumen General</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <h3>Bailarines Registrados</h3>
                <p><?php echo $total_bailarines; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-purple">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <h3>Total Vestuarios</h3>
                <p><?php echo $total_vestuarios; ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3>Solicitudes Pendientes</h3>
                <p style="color: var(--accent-color);"><?php echo $pending_loans; ?></p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="?view=bailarines" class="quick-action-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Agregar Bailarín
            </a>
            <a href="?view=vestuarios" class="quick-action-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Agregar Vestuario
            </a>
            <a href="?view=prestamos" class="quick-action-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Ver Préstamos
            </a>
        </div>

        <?php if (count($recent_loans) > 0): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="recent-list">
                <h3>Últimos Préstamos</h3>
                <?php foreach ($recent_loans as $loan): ?>
                <div class="recent-item">
                    <div class="recent-item-info">
                        <span class="recent-item-title"><?php echo htmlspecialchars($loan['vestuario']); ?></span>
                        <span class="recent-item-subtitle"><?php echo htmlspecialchars($loan['bailarin']); ?></span>
                    </div>
                    <span class="badge badge-<?php echo $loan['estado']; ?>"><?php echo $loan['estado']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php
        break;
    default:
        // Redirect to dashboard if unknown view
        header("Location: admin.php?view=dashboard");
        exit;
}

$content = ob_get_clean();
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
            --accent-color: #3b82f6;
            --accent-hover: #2563eb;
            --danger-color: #ef4444;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --info-color: #0ea5e9;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
            
            --badge-pending: #fef3c7;
            --badge-pending-text: #92400e;
            --badge-approved: #d1fae5;
            --badge-approved-text: #065f46;
            --badge-rejected: #fee2e2;
            --badge-rejected-text: #991b1b;
            --badge-returned: #dbeafe;
            --badge-returned-text: #1e40af;
            --badge-male: #dbeafe;
            --badge-male-text: #1e40af;
            --badge-female: #fce7f3;
            --badge-female-text: #9d174d;
            --badge-other: #f3f4f6;
            --badge-other-text: #374151;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--primary-bg);
            margin: 0;
            color: var(--text-main);
        }
        .admin-layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
        }
        
        /* Header Styling */
        .admin-header {
            width: 100%;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            padding: 1rem 2rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            z-index: 20;
            position: sticky;
            top: 0;
        }
        .admin-header h2 {
            text-align: left;
            margin-bottom: 0;
            font-size: 1.25rem;
            margin-right: 2rem;
        }
        .admin-header nav {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
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
            .admin-header {
                padding: 1rem;
                flex-wrap: wrap;
            }
            .menu-toggle {
                display: flex;
            }
            .admin-header nav {
                display: none;
                width: 100%;
                flex-direction: column;
                padding-top: 1rem;
                gap: 0.5rem;
            }
            .admin-header nav.active {
                display: flex;
            }
            .admin-header a {
                width: 100%;
            }
            .logout-link {
                margin-left: 0;
            }
            .admin-content {
                padding: 1rem;
            }
        }
        .admin-header a {
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
        .admin-header a:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateY(-2px);
        }
        .admin-header a.active {
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
            padding: 2rem 3rem;
            overflow-y: auto;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
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

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .badge-pending { background: var(--badge-pending); color: var(--badge-pending-text); }
        .badge-approved { background: var(--badge-approved); color: var(--badge-approved-text); }
        .badge-rejected { background: var(--badge-rejected); color: var(--badge-rejected-text); }
        .badge-returned { background: var(--badge-returned); color: var(--badge-returned-text); }
        .badge-male { background: var(--badge-male); color: var(--badge-male-text); }
        .badge-female { background: var(--badge-female); color: var(--badge-female-text); }
        .badge-other { background: var(--badge-other); color: var(--badge-other-text); }
        .badge-low-stock { background: #fee2e2; color: #991b1b; }

        /* User Section */
        .user-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            margin-left: 1rem;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .user-name {
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Notification Badge */
        .nav-badge {
            position: relative;
        }
        .nav-badge::after {
            content: attr(data-count);
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger-color);
            color: white;
            font-size: 0.625rem;
            padding: 2px 6px;
            border-radius: 9999px;
            min-width: 18px;
            text-align: center;
        }
        .nav-badge:empty::after,
        .nav-badge[data-count="0"]::after { display: none; }

        /* Nav Icons */
        .nav-icon {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .quick-action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .quick-action-btn:hover {
            border-color: var(--accent-color);
            color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .quick-action-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Recent Items */
        .recent-list {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
        }
        .recent-list h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: var(--text-main);
        }
        .recent-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        .recent-item:last-child { border-bottom: none; }
        .recent-item-info {
            display: flex;
            flex-direction: column;
        }
        .recent-item-title {
            font-weight: 500;
            color: var(--text-main);
            font-size: 0.875rem;
        }
        .recent-item-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Stat Card Icons */
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .stat-icon svg {
            width: 24px;
            height: 24px;
        }
        .stat-icon-blue { background: #dbeafe; color: #2563eb; }
        .stat-icon-green { background: #d1fae5; color: #059669; }
        .stat-icon-orange { background: #fef3c7; color: #d97706; }
        .stat-icon-purple { background: #ede9fe; color: #7c3aed; }

        /* Improved Modal */
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
            animation: fadeIn 0.2s ease;
        }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content {
            background: var(--card-bg);
            border-radius: 1rem;
            padding: 2rem;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            animation: slideUp 0.3s ease;
            position: relative;
        }
        .close-btn {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            color: var(--text-muted);
            cursor: pointer;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        .close-btn:hover { background: var(--primary-bg); color: var(--text-main); }

        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Form Improvements */
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: var(--primary-bg);
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        }

        /* Search Input */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: var(--card-bg);
        }
        .search-box input:focus {
            outline: none;
            border-color: var(--accent-color);
        }
        .search-box svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-muted);
        }

        /* Filter Select */
        .filter-bar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        .filter-bar select {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: var(--card-bg);
            cursor: pointer;
        }

        /* Table Improvements */
        table tbody tr {
            transition: background-color 0.2s;
        }
        table tbody tr:hover td {
            background-color: #f1f5f9;
        }

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
        .toast-warning { background: var(--warning-color); }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
            .admin-content { padding: 1rem; }
            .quick-actions { flex-direction: column; }
            .user-name { display: none; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <header class="admin-header">
            <h2>BFC Panel</h2>
            <button class="menu-toggle" id="menuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <nav id="adminNav">
                <a href="?view=dashboard" class="<?php echo $view == 'dashboard' ? 'active' : ''; ?>">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="?view=bailarines" class="<?php echo $view == 'bailarines' ? 'active' : ''; ?>">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Bailarines
                </a>
                <a href="?view=vestuarios" class="<?php echo $view == 'vestuarios' ? 'active' : ''; ?>">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Vestuario
                </a>
                <a href="?view=funciones" class="<?php echo $view == 'funciones' ? 'active' : ''; ?>">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Funciones
                </a>
                <a href="?view=prestamos" class="nav-badge <?php echo $view == 'prestamos' ? 'active' : ''; ?>" <?php echo $pending_loans > 0 ? 'data-count="'.$pending_loans.'"' : ''; ?>>
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Préstamos
                </a>
                <div class="user-section">
                    <div class="user-avatar">A</div>
                    <span class="user-name">Admin</span>
                </div>
                <a href="logout.php" class="logout-link">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Cerrar Sesión
                </a>
            </nav>
        </header>
        <main class="admin-content">
            <?php if ($message): ?>
                <div class="alert"><?php echo $message; ?></div>
            <?php endif; ?>

            <?php echo $content; ?>
        </main>
    </div>
    <div class="toast-container" id="toastContainer"></div>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const adminNav = document.getElementById('adminNav');

        menuToggle.addEventListener('click', () => {
            adminNav.classList.toggle('active');
        });

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
