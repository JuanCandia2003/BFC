<?php
// includes/admin/prestamos.php

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    $stmt = $pdo->prepare("SELECT * FROM prestamos WHERE id = ?");
    $stmt->execute([$id]);
    $prestamo = $stmt->fetch();
    
    if ($prestamo) {
        if ($action == 'approve' && $prestamo['estado'] == 'pendiente') {
            $stmt = $pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ? AND cantidad_disponible > 0");
            $res = $stmt->execute([$prestamo['vestuario_id']]);
            
            if ($stmt->rowCount() > 0) {
                $pdo->prepare("UPDATE prestamos SET estado = 'aprobado' WHERE id = ?")->execute([$id]);
            } else {
                echo "<script>alert('Error: No hay inventario disponible.');</script>";
            }
        } elseif ($action == 'reject' && $prestamo['estado'] == 'pendiente') {
            $pdo->prepare("UPDATE prestamos SET estado = 'rechazado' WHERE id = ?")->execute([$id]);
        } elseif ($action == 'return' && $prestamo['estado'] == 'aprobado') {
            $pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?")->execute([$prestamo['vestuario_id']]);
            $pdo->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = NOW() WHERE id = ?")->execute([$id]);
        }
    }
    echo "<script>window.location.href='admin.php?view=prestamos';</script>";
    exit;
}

// Filter
$filter_estado = isset($_GET['estado']) ? $_GET['estado'] : 'all';

$sql = "SELECT p.*, b.nombre as bailarin, v.nombre as vestuario, f.nombre as funcion, v.imagen as v_imagen
        FROM prestamos p 
        JOIN bailarines b ON p.bailarin_id = b.id 
        JOIN vestuarios v ON p.vestuario_id = v.id 
        JOIN funciones f ON p.funcion_id = f.id";

if ($filter_estado != 'all') {
    $sql .= " WHERE p.estado = '$filter_estado'";
}

$sql .= " ORDER BY p.fecha_solicitud DESC";

$prestamos = $pdo->query($sql)->fetchAll();
?>

<div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <h2 style="margin: 0;">Gestión de Préstamos</h2>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
        <input type="hidden" name="view" value="prestamos">
        <label style="font-size: 0.875rem; font-weight: 500;">Filtrar por estado:</label>
        <select name="estado" onchange="this.form.submit()" style="min-width: 140px;">
            <option value="all" <?php echo $filter_estado == 'all' ? 'selected' : ''; ?>>Todos</option>
            <option value="pendiente" <?php echo $filter_estado == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
            <option value="aprobado" <?php echo $filter_estado == 'aprobado' ? 'selected' : ''; ?>>Aprobado</option>
            <option value="rechazado" <?php echo $filter_estado == 'rechazado' ? 'selected' : ''; ?>>Rechazado</option>
            <option value="devuelto" <?php echo $filter_estado == 'devuelto' ? 'selected' : ''; ?>>Devuelto</option>
        </select>
        <?php if($filter_estado != 'all'): ?>
        <a href="admin.php?view=prestamos" class="btn-submit" style="padding: 0.5rem 1rem; background: #6b7280; text-decoration: none;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Bailarín</th>
            <th>Vestuario</th>
            <th>Función</th>
            <th>Observaciones</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prestamos as $p): ?>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 32px; height: 32px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 600;">
                            <?php echo strtoupper(substr($p['bailarin'], 0, 1)); ?>
                        </div>
                        <span><?php echo htmlspecialchars($p['bailarin']); ?></span>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <?php if ($p['v_imagen']): ?>
                            <img src="assets/images/<?php echo htmlspecialchars($p['v_imagen']); ?>" style="width: 36px; height: 36px; object-fit: cover; border-radius: 0.375rem;">
                        <?php else: ?>
                            <div style="width: 36px; height: 36px; background: #e2e8f0; border-radius: 0.375rem;"></div>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($p['vestuario']); ?>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($p['funcion']); ?></td>
                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo htmlspecialchars($p['observaciones']); ?>">
                    <?php echo htmlspecialchars($p['observaciones'] ?: '-'); ?>
                </td>
                <td>
                    <?php 
                    $badge_class = '';
                    switch($p['estado']) {
                        case 'pendiente': $badge_class = 'badge-pending'; break;
                        case 'aprobado': $badge_class = 'badge-approved'; break;
                        case 'rechazado': $badge_class = 'badge-rejected'; break;
                        case 'devuelto': $badge_class = 'badge-returned'; break;
                    }
                    ?>
                    <span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($p['estado']); ?></span>
                </td>
                <td>
                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                        <?php echo date('d/m/Y', strtotime($p['fecha_solicitud'])); ?>
                    </div>
                </td>
                <td>
                    <?php if ($p['estado'] == 'pendiente'): ?>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="admin.php?view=prestamos&action=approve&id=<?php echo $p['id']; ?>" class="action-btn" style="background: var(--success-color);" title="Aprobar">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Aprobar
                            </a>
                            <a href="admin.php?view=prestamos&action=reject&id=<?php echo $p['id']; ?>" class="action-btn" style="background: var(--danger-color);" title="Rechazar" onclick="return confirm('¿Estás seguro de rechazar este préstamo?');">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Rechazar
                            </a>
                        </div>
                    <?php elseif ($p['estado'] == 'aprobado'): ?>
                        <a href="admin.php?view=prestamos&action=return&id=<?php echo $p['id']; ?>" class="action-btn" style="background: var(--info-color);" title="Marcar devuelto">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Devolver
                        </a>
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-size: 0.75rem;">Completado</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
