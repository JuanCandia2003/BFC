<?php
// includes/admin/funciones.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM funciones WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='admin.php?view=funciones';</script>";
}

// Handle Add/Edit
$edit_mode = false;
$f_data = ['nombre' => '', 'fecha' => '', 'lugar' => '', 'descripcion' => '', 'id' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM funciones WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $f_data = $stmt->fetch();
    // Format date for datetime-local input (YYYY-MM-DDTHH:MM)
    $f_data['fecha'] = date('Y-m-d\TH:i', strtotime($f_data['fecha']));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_funcion'])) {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $lugar = $_POST['lugar'];
    $descripcion = $_POST['descripcion'];
    
    if ($edit_mode) {
        $stmt = $pdo->prepare("UPDATE funciones SET nombre=?, fecha=?, lugar=?, descripcion=? WHERE id=?");
        $stmt->execute([$nombre, $fecha, $lugar, $descripcion, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO funciones (nombre, fecha, lugar, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $fecha, $lugar, $descripcion]);
    }
    echo "<script>window.location.href='admin.php?view=funciones';</script>";
}

$stmt = $pdo->query("SELECT * FROM funciones ORDER BY fecha DESC");
$funciones = $stmt->fetchAll();
?>

<div class="section-header">
    <h2>Gestión de Funciones</h2>
    <button id="addBtn" class="btn-submit">Agregar Nueva Función</button>
</div>

<!-- Modal -->
<div id="funcionModal" class="modal <?php echo $edit_mode ? 'active' : ''; ?>">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3><?php echo $edit_mode ? 'Editar' : 'Agregar'; ?> Función</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $f_data['id']; ?>">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <input type="text" name="nombre" placeholder="Nombre del Evento" value="<?php echo htmlspecialchars($f_data['nombre']); ?>" required>
                <input type="datetime-local" name="fecha" value="<?php echo $f_data['fecha']; ?>" required>
                <input type="text" name="lugar" placeholder="Lugar" value="<?php echo htmlspecialchars($f_data['lugar']); ?>">
                <textarea name="descripcion" placeholder="Descripción" rows="3"><?php echo htmlspecialchars($f_data['descripcion']); ?></textarea>
            </div>
            <button type="submit" name="save_funcion" class="btn-submit" style="margin-top: 10px;">Guardar</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('funcionModal');
    const btn = document.getElementById('addBtn');
    const span = document.getElementsByClassName('close-btn')[0];

    btn.onclick = function() {
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=funciones';
        <?php else: ?>
            modal.classList.add('active');
        <?php endif; ?>
    }

    span.onclick = function() {
        modal.classList.remove('active');
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=funciones';
        <?php endif; ?>
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.remove('active');
            <?php if($edit_mode): ?>
                window.location.href = 'admin.php?view=funciones';
            <?php endif; ?>
        }
    }
</script>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Lugar</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($funciones as $f): ?>
            <tr>
                <td><?php echo htmlspecialchars($f['nombre']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($f['fecha'])); ?></td>
                <td><?php echo htmlspecialchars($f['lugar']); ?></td>
                <td><?php echo htmlspecialchars(substr($f['descripcion'], 0, 50)) . '...'; ?></td>
                <td>
                    <a href="admin.php?view=funciones&edit=<?php echo $f['id']; ?>" class="action-btn btn-edit">Editar</a>
                    <a href="admin.php?view=funciones&delete=<?php echo $f['id']; ?>" onclick="return confirm('¿Seguro?')" class="action-btn btn-delete">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
