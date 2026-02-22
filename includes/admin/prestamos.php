<?php
// includes/admin/prestamos.php

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];
    
    // Get current loan data to update inventory
    $stmt = $pdo->prepare("SELECT * FROM prestamos WHERE id = ?");
    $stmt->execute([$id]);
    $prestamo = $stmt->fetch();
    
    if ($prestamo) {
        if ($action == 'approve' && $prestamo['estado'] == 'pendiente') {
            // Deduct inventory
            $stmt = $pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ? AND cantidad_disponible > 0");
            $res = $stmt->execute([$prestamo['vestuario_id']]);
            
            if ($stmt->rowCount() > 0) {
                // Update loan status
                $pdo->prepare("UPDATE prestamos SET estado = 'aprobado' WHERE id = ?")->execute([$id]);
            } else {
                echo "<script>alert('Error: No hay inventario disponible.');</script>";
            }
        } elseif ($action == 'return' && $prestamo['estado'] == 'aprobado') {
            // Restore inventory
            $pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?")->execute([$prestamo['vestuario_id']]);
            // Update loan status
            $pdo->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = NOW() WHERE id = ?")->execute([$id]);
        }
    }
    echo "<script>window.location.href='admin.php?view=prestamos';</script>";
}

// Fetch Loans
$sql = "SELECT p.*, b.nombre as bailarin, v.nombre as vestuario, f.nombre as funcion 
        FROM prestamos p 
        JOIN bailarines b ON p.bailarin_id = b.id 
        JOIN vestuarios v ON p.vestuario_id = v.id 
        JOIN funciones f ON p.funcion_id = f.id 
        ORDER BY p.fecha_solicitud DESC";
$prestamos = $pdo->query($sql)->fetchAll();
?>

<div class="section-header">
    <h2>Gestión de Préstamos</h2>
</div>

<table>
    <thead>
        <tr>
            <th>Bailarín</th>
            <th>Vestuario</th>
            <th>Función</th>
            <th>Observaciones</th>
            <th>Estado</th>
            <th>Fecha Solicitud</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($prestamos as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['bailarin']); ?></td>
                <td><?php echo htmlspecialchars($p['vestuario']); ?></td>
                <td><?php echo htmlspecialchars($p['funcion']); ?></td>
                <td><?php echo htmlspecialchars($p['observaciones']); ?></td>
                <td>
                    <span style="padding: 5px; border-radius: 4px; background: 
                        <?php 
                        switch($p['estado']) {
                            case 'pendiente': echo '#ffeeba'; break;
                            case 'aprobado': echo '#d4edda'; break;
                            case 'devuelto': echo '#c3e6cb'; break;
                            case 'rechazado': echo '#f8d7da'; break;
                        }
                        ?>">
                        <?php echo ucfirst($p['estado']); ?>
                    </span>
                </td>
                <td><?php echo date('d/m H:i', strtotime($p['fecha_solicitud'])); ?></td>
                <td>
                    <?php if ($p['estado'] == 'pendiente'): ?>
                        <a href="admin.php?view=prestamos&action=approve&id=<?php echo $p['id']; ?>" class="action-btn btn-approve">Aprobar</a>
                    <?php elseif ($p['estado'] == 'aprobado'): ?>
                        <a href="admin.php?view=prestamos&action=return&id=<?php echo $p['id']; ?>" class="action-btn btn-return">Devolver</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
