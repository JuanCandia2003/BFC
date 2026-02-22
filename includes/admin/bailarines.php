<?php
// includes/admin/bailarines.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM bailarines WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='admin.php?view=bailarines';</script>";
}

// Handle Add/Edit
$edit_mode = false;
$bailarin_data = ['nombre' => '', 'email' => '', 'telefono' => '', 'genero' => 'Otro', 'id' => ''];

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $stmt = $pdo->prepare("SELECT * FROM bailarines WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $bailarin_data = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_bailarin'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    
    if ($edit_mode) {
        $stmt = $pdo->prepare("UPDATE bailarines SET nombre=?, email=?, telefono=?, genero=? WHERE id=?");
        $stmt->execute([$nombre, $email, $telefono, $genero, $_POST['id']]);
    } else {
        $password = password_hash('bailarin123', PASSWORD_BCRYPT); // Default password
        $stmt = $pdo->prepare("INSERT INTO bailarines (nombre, email, telefono, genero, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $telefono, $genero, $password]);
    }
    echo "<script>window.location.href='admin.php?view=bailarines';</script>";
}

// Fetch All
$stmt = $pdo->query("SELECT * FROM bailarines ORDER BY nombre ASC");
$bailarines = $stmt->fetchAll();
?>

<div class="section-header">
    <h2>Gestión de Bailarines</h2>
    <button id="addBtn" class="btn-submit">Agregar Bailarín</button>
</div>

<!-- Modal -->
<div id="bailarinModal" class="modal <?php echo $edit_mode ? 'active' : ''; ?>">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3><?php echo $edit_mode ? 'Editar' : 'Agregar'; ?> Bailarín</h3>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $bailarin_data['id']; ?>">
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <input type="text" name="nombre" placeholder="Nombre Completo" value="<?php echo htmlspecialchars($bailarin_data['nombre']); ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($bailarin_data['email']); ?>" required>
                <input type="text" name="telefono" placeholder="Teléfono" value="<?php echo htmlspecialchars($bailarin_data['telefono']); ?>">
                <select name="genero">
                    <option value="M" <?php if($bailarin_data['genero'] == 'M') echo 'selected'; ?>>Masculino</option>
                    <option value="F" <?php if($bailarin_data['genero'] == 'F') echo 'selected'; ?>>Femenino</option>
                    <option value="Otro" <?php if($bailarin_data['genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                </select>
            </div>
            <button type="submit" name="save_bailarin" class="btn-submit" style="margin-top: 10px;">Guardar</button>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('bailarinModal');
    const btn = document.getElementById('addBtn');
    const span = document.getElementsByClassName('close-btn')[0];

    btn.onclick = function() {
        // Reset form if needed, or just open. For simplicity in this PHP value-filled form, just open.
        // If we were using AJAX, we'd clear values. Here, if we click Add after Edit, values might persist if we don't clear URL.
        // But to keep it simple and consistent with previous "Add" behavior (which was separate or cleared), 
        // we should probably redirect to clear 'edit' param if we are in edit mode.
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=bailarines';
        <?php else: ?>
            modal.classList.add('active');
        <?php endif; ?>
    }

    span.onclick = function() {
        modal.classList.remove('active');
        <?php if($edit_mode): ?>
            window.location.href = 'admin.php?view=bailarines'; // Clear edit mode on close
        <?php endif; ?>
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.classList.remove('active');
            <?php if($edit_mode): ?>
                window.location.href = 'admin.php?view=bailarines';
            <?php endif; ?>
        }
    }
</script>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bailarines as $b): ?>
            <tr>
                <td><?php echo htmlspecialchars($b['nombre']); ?></td>
                <td><?php echo htmlspecialchars($b['email']); ?></td>
                <td><?php echo htmlspecialchars($b['telefono']); ?></td>
                <td><?php echo htmlspecialchars($b['genero']); ?></td>
                <td>
                    <a href="admin.php?view=bailarines&edit=<?php echo $b['id']; ?>" class="action-btn btn-edit">Editar</a>
                    <a href="admin.php?view=bailarines&delete=<?php echo $b['id']; ?>" onclick="return confirm('¿Seguro?')" class="action-btn btn-delete">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
