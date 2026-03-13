<?php
// includes/admin/vestuarios.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM vestuarios WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='admin.php?view=vestuarios';</script>";
    exit;
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
    $imagen = $v_data['imagen'];

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = uploadImage($_FILES['imagen']);
    }
    
    if ($edit_mode) {
        $stmt = $pdo->prepare("UPDATE vestuarios SET nombre=?, descripcion=?, talla=?, genero=?, cantidad_total=?, imagen=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $talla, $genero, $cantidad, $imagen, $_POST['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO vestuarios (nombre, descripcion, talla, genero, cantidad_total, cantidad_disponible, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $talla, $genero, $cantidad, $cantidad, $imagen]);
    }
    echo "<script>window.location.href='admin.php?view=vestuarios';</script>";
    exit;
}

// Fetch Data with Search and Filter
$query = "SELECT * FROM vestuarios WHERE 1=1";
$params = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $query .= " AND nombre LIKE ?";
    $params[] = "%" . $_GET['q'] . "%";
}

if (isset($_GET['genero']) && !empty($_GET['genero']) && $_GET['genero'] != 'all') {
    $query .= " AND genero = ?";
    $params[] = $_GET['genero'];
}

$query .= " ORDER BY nombre ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vestuarios = $stmt->fetchAll();

$search = isset($_GET['q']) ? $_GET['q'] : '';
$filter_genero = isset($_GET['genero']) ? $_GET['genero'] : 'all';
?>

<div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <h2 style="margin: 0;">Gestión de Vestuario</h2>
    <button id="addBtn" class="btn-submit">
        <svg style="width:18px;height:18px;margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Agregar Vestuario
    </button>
</div>

<div class="filter-bar">
    <form method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap; width: 100%;">
        <input type="hidden" name="view" value="vestuarios">
        <div class="search-box" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="q" placeholder="Buscar vestuario..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <select name="genero" style="min-width: 120px;">
            <option value="all" <?php echo $filter_genero == 'all' ? 'selected' : ''; ?>>Todos</option>
            <option value="Hombre" <?php echo $filter_genero == 'Hombre' ? 'selected' : ''; ?>>Hombre</option>
            <option value="Mujer" <?php echo $filter_genero == 'Mujer' ? 'selected' : ''; ?>>Mujer</option>
            <option value="Unisex" <?php echo $filter_genero == 'Unisex' ? 'selected' : ''; ?>>Unisex</option>
        </select>
        <button type="submit" class=" style="padding:btn-submit" 0.5rem 1rem;">Filtrar</button>
        <?php if($search || $filter_genero != 'all'): ?>
        <a href="admin.php?view=vestuarios" class="btn-submit" style="padding: 0.5rem 1rem; background: #6b7280; text-decoration: none;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<!-- Modal -->
<div id="vestuarioModal" class="modal <?php echo $edit_mode ? 'active' : ''; ?>">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3><?php echo $edit_mode ? 'Editar' : 'Agregar'; ?> Vestuario</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $v_data['id']; ?>">
            <div class="form-group">
                <label>Nombre del Vestuario</label>
                <input type="text" name="nombre" placeholder="Ej: Traje de Caperucita" value="<?php echo htmlspecialchars($v_data['nombre']); ?>" required>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>Talla</label>
                    <input type="text" name="talla" placeholder="Ej: S, M, L" value="<?php echo htmlspecialchars($v_data['talla']); ?>">
                </div>
                <div class="form-group">
                    <label>Género</label>
                    <select name="genero">
                        <option value="Hombre" <?php if($v_data['genero'] == 'Hombre') echo 'selected'; ?>>Hombre</option>
                        <option value="Mujer" <?php if($v_data['genero'] == 'Mujer') echo 'selected'; ?>>Mujer</option>
                        <option value="Unisex" <?php if($v_data['genero'] == 'Unisex') echo 'selected'; ?>>Unisex</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Cantidad Total</label>
                <input type="number" name="cantidad_total" value="<?php echo $v_data['cantidad_total']; ?>" required min="1">
            </div>
            <div class="form-group">
                <label>Imagen</label>
                <input type="file" name="imagen" accept="image/*" style="padding: 0.5rem;">
                <?php if($v_data['imagen']): ?>
                <small style="color: var(--text-muted);">Imagen actual: <?php echo htmlspecialchars($v_data['imagen']); ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="descripcion" placeholder="Descripción del vestuario..." rows="3"><?php echo htmlspecialchars($v_data['descripcion']); ?></textarea>
            </div>
            <button type="submit" name="save_vestuario" class="btn-submit" style="width: 100%; margin-top: 0.5rem;">Guardar</button>
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

<table>
    <thead>
        <tr>
            <th style="width: 70px;">Imagen</th>
            <th>Nombre</th>
            <th>Detalles</th>
            <th>Stock</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($vestuarios as $v): ?>
            <tr>
                <td>
                    <?php if ($v['imagen']): ?>
                        <img src="assets/images/<?php echo htmlspecialchars($v['imagen']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.5rem;">
                    <?php else: ?>
                        <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                            <svg style="width: 24px; height: 24px; color: #94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                </td>
                <td><strong><?php echo htmlspecialchars($v['nombre']); ?></strong></td>
                <td>
                    <span style="font-size: 0.875rem; color: var(--text-muted);">
                        <?php echo htmlspecialchars($v['talla']); ?> • 
                        <span class="badge <?php echo $v['genero'] == 'Hombre' ? 'badge-male' : ($v['genero'] == 'Mujer' ? 'badge-female' : 'badge-other'); ?>">
                            <?php echo $v['genero']; ?>
                        </span>
                    </span>
                </td>
                <td>
                    <?php if ($v['cantidad_disponible'] < 2): ?>
                        <span class="badge badge-low-stock">
                            <svg style="width:12px;height:12px;margin-right:4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <?php echo $v['cantidad_disponible']; ?>/<?php echo $v['cantidad_total']; ?>
                        </span>
                    <?php else: ?>
                        <span style="font-weight: 600; color: <?php echo $v['cantidad_disponible'] > 0 ? 'var(--success-color)' : 'var(--danger-color)'; ?>">
                            <?php echo $v['cantidad_disponible']; ?>/<?php echo $v['cantidad_total']; ?>
                        </span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="admin.php?view=vestuarios&edit=<?php echo $v['id']; ?>" class="action-btn btn-edit" title="Editar">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Editar
                    </a>
                    <a href="admin.php?view=vestuarios&delete=<?php echo $v['id']; ?>" onclick="event.preventDefault(); if(confirm('¿Está seguro de eliminar este vestuario?')) { window.location.href=this.href; }" class="action-btn btn-delete" title="Eliminar">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Eliminar
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
