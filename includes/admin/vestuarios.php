<?php
// includes/admin/vestuarios.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM vestuarios WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='admin.php?view=vestuarios';</script>";
}

// Handle Add/Edit
$edit_mode = false;
$v_data = ['nombre' => '', 'descripcion' => '', 'talla' => '', 'genero' => 'Unisex', 'cantidad_total' => 1, 'id' => '', 'imagen' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM vestuarios WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $v_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_vestuario'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $talla = $_POST['talla'];
    $genero = $_POST['genero'];
    $cantidad = $_POST['cantidad_total'];
    $imagen = $v_data['imagen']; // Keep old image by default

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = uploadImage($_FILES['imagen']);
    }
    
    // Logic: If updating quantity, we should ideally adjust available quantity too, but keeping it simple for now. 
    // Assuming available = total on create, and tricky on update. 
    // For this simple version, reset available = total if creating.
    
    if ($edit_mode) {
        // Recalculate available based on loans? Too complex for now. 
        // Just update total. Admin can manually manage inventory if needed or we assume logic elsewhere.
        // Let's just update total.
        $stmt = $pdo->prepare("UPDATE vestuarios SET nombre=?, descripcion=?, talla=?, genero=?, cantidad_total=?, imagen=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $talla, $genero, $cantidad, $imagen, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO vestuarios (nombre, descripcion, talla, genero, cantidad_total, cantidad_disponible, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $talla, $genero, $cantidad, $cantidad, $imagen]);
    }
    echo "<script>window.location.href='admin.php?view=vestuarios';</script>";
    echo "<script>window.location.href='admin.php?view=vestuarios';</script>";
}

// Fetch Data with Search
$query = "SELECT * FROM vestuarios";
$params = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query .= " WHERE nombre LIKE ?";
    $params[] = "%" . $_GET['q'] . "%";
}

$query .= " ORDER BY nombre ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vestuarios = $stmt->fetchAll();
?>

<div class="section-header">
    <h2>Gestión de Vestuario</h2>
    <button id="addBtn" class="btn-submit">Agregar Vestuario</button>
</div>

<!-- Modal -->
<div id="vestuarioModal" class="modal <?php echo $edit_mode ? 'active' : ''; ?>">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3><?php echo $edit_mode ? 'Editar' : 'Agregar'; ?> Vestuario</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $v_data['id']; ?>">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <input type="text" name="nombre" placeholder="Nombre Vestuario" value="<?php echo htmlspecialchars($v_data['nombre']); ?>" required>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <input type="text" name="talla" placeholder="Talla" value="<?php echo htmlspecialchars($v_data['talla']); ?>">
                    <select name="genero">
                        <option value="Hombre" <?php if($v_data['genero'] == 'Hombre') echo 'selected'; ?>>Hombre</option>
                        <option value="Mujer" <?php if($v_data['genero'] == 'Mujer') echo 'selected'; ?>>Mujer</option>
                        <option value="Unisex" <?php if($v_data['genero'] == 'Unisex') echo 'selected'; ?>>Unisex</option>
                    </select>
                </div>
                <input type="number" name="cantidad_total" placeholder="Cantidad Total" value="<?php echo $v_data['cantidad_total']; ?>" required min="1">
                <input type="file" name="imagen" accept="image/*">
                <textarea name="descripcion" placeholder="Descripción" rows="3"><?php echo htmlspecialchars($v_data['descripcion']); ?></textarea>
            </div>
            <button type="submit" name="save_vestuario" class="btn-submit" style="margin-top: 10px;">Guardar</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('vestuarioModal');
    const btn = document.getElementById('addBtn');
    const span = document.getElementsByClassName('close-btn')[0];

    btn.onclick = function() {
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=vestuarios';
        <?php else: ?>
            modal.classList.add('active');
        <?php endif; ?>
    }

    span.onclick = function() {
        modal.classList.remove('active');
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=vestuarios';
        <?php endif; ?>
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.remove('active');
            <?php if($edit_mode): ?>
                window.location.href = 'admin.php?view=vestuarios';
            <?php endif; ?>
        }
    }
</script>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
    <h3>Listado de Vestuarios</h3>
    <form method="GET" style="display: flex; gap: 5px;">
        <input type="hidden" name="view" value="vestuarios">
        <input type="text" name="q" placeholder="Buscar vestuario..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" style="padding: 5px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" class="btn-submit" style="padding: 5px 10px; width: auto; margin: 0;">Buscar</button>
        <?php if(isset($_GET['q'])): ?>
            <a href="admin.php?view=vestuarios" style="padding: 5px 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 4px; display: inline-block;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Talla - Género</th>
            <th>Total</th>
            <th>Disp.</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vestuarios as $v): ?>
            <tr>
                <td>
                    <?php if ($v['imagen']): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($v['imagen']); ?>" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($v['nombre']); ?></td>
                <td><?php echo htmlspecialchars($v['talla'] . ' - ' . $v['genero']); ?></td>
                <td><?php echo $v['cantidad_total']; ?></td>
                <td><?php echo $v['cantidad_disponible']; ?></td>
                <td>
                    <a href="admin.php?view=vestuarios&edit=<?php echo $v['id']; ?>" class="action-btn btn-edit">Editar</a>
                    <a href="admin.php?view=vestuarios&delete=<?php echo $v['id']; ?>" onclick="return confirm('¿Seguro?')" class="action-btn btn-delete">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
