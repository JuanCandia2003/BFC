<?php
// includes/admin/bailarines.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM bailarines WHERE id = ?");
    $stmt->execute([$id]);
    echo "<script>window.location.href='admin.php?view=bailarines';</script>";
    exit;
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

if (isset($_GET['reset_password']) && is_numeric($_GET['reset_password'])) {
    $new_password = password_hash('bailarin123', PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("UPDATE bailarines SET password = ? WHERE id = ?");
    $stmt->execute([$new_password, $_GET['reset_password']]);
    echo "<script>showToast('Contraseña reseteada a: bailarin123', 'success');</script>";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_bailarin'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $genero = $_POST['genero'];
    
    // Validate unique email
    if (!$edit_mode || ($edit_mode && $email !== $bailarin_data['email'])) {
        $stmt = $pdo->prepare("SELECT id FROM bailarines WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo "<script>showToast('El email ya está registrado', 'error');</script>";
        } else {
            saveBailarin($pdo, $edit_mode, $bailarin_data, $_POST);
        }
    } else {
        saveBailarin($pdo, $edit_mode, $bailarin_data, $_POST);
    }
}

function saveBailarin($pdo, $edit_mode, $bailarin_data, $post) {
    $nombre = $post['nombre'];
    $email = $post['email'];
    $telefono = $post['telefono'];
    $genero = $post['genero'];
    
    if ($edit_mode) {
        $stmt = $pdo->prepare("UPDATE bailarines SET nombre=?, email=?, telefono=?, genero=? WHERE id=?");
        $stmt->execute([$nombre, $email, $telefono, $genero, $post['id']]);
    } else {
        $password = password_hash('bailarin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO bailarines (nombre, email, telefono, genero, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $email, $telefono, $genero, $password]);
    }
    echo "<script>showToast('Bailarín guardado correctamente', 'success');</script>";
    echo "<script>setTimeout(() => window.location.href='admin.php?view=bailarines', 1000);</script>";
    exit;
}

// Search & Filter
$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$filter_genero = isset($_GET['genero']) ? $_GET['genero'] : 'all';

$sql = "SELECT * FROM bailarines WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (nombre LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($filter_genero != 'all') {
    $sql .= " AND genero = ?";
    $params[] = $filter_genero;
}

$sql .= " ORDER BY nombre ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
    $bailarines = $stmt->fetchAll();

// Get active loans count for each bailarin (only approved loans)
$active_loans = [];
$stmt = $pdo->query("SELECT bailarin_id, COUNT(*) as count FROM prestamos WHERE estado = 'aprobado' GROUP BY bailarin_id");
foreach ($stmt->fetchAll() as $row) {
    $active_loans[$row['bailarin_id']] = $row['count'];
}
?>

<div class="section-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="margin: 0;">Gestión de Bailarines</h2>
        <?php if (count($bailarines) > 0): ?>
        <span style="font-size: 0.875rem; color: var(--text-muted);">
            <?php echo count($bailarines); ?> bailarín<?php echo count($bailarines) != 1 ? 'es' : ''; ?> encontrado<?php echo count($bailarines) != 1 ? 's' : ''; ?>
        </span>
        <?php endif; ?>
    </div>
    <button id="addBtn" class="btn-submit">
        <svg style="width:18px;height:18px;margin-right:6px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Agregar Bailarín
    </button>
</div>

<div class="filter-bar" style="background: var(--card-bg); padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color); margin-bottom: 1.5rem;">
    <form method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap; width: 100%; align-items: center;">
        <input type="hidden" name="view" value="bailarines">
        <div class="search-box" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="q" placeholder="Buscar por nombre o email..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <select name="genero" style="min-width: 120px;">
            <option value="all" <?php echo $filter_genero == 'all' ? 'selected' : ''; ?>>Todos los géneros</option>
            <option value="M" <?php echo $filter_genero == 'M' ? 'selected' : ''; ?>>Masculino</option>
            <option value="F" <?php echo $filter_genero == 'F' ? 'selected' : ''; ?>>Femenino</option>
            <option value="Otro" <?php echo $filter_genero == 'Otro' ? 'selected' : ''; ?>>Otro</option>
        </select>
        <button type="submit" class="btn-submit" style="padding: 0.5rem 1rem;">Filtrar</button>
        <?php if($search || $filter_genero != 'all'): ?>
        <a href="admin.php?view=bailarines" class="btn-submit" style="padding: 0.5rem 1rem; background: #6b7280; text-decoration: none;">Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<!-- Modal Agregar/Editar -->
<div id="bailarinModal" class="modal <?php echo $edit_mode ? 'active' : ''; ?>">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h3><?php echo $edit_mode ? 'Editar' : 'Agregar'; ?> Bailarín</h3>
        <form method="POST" id="bailarinForm">
            <input type="hidden" name="id" value="<?php echo $bailarin_data['id']; ?>">
            <div class="form-group">
                <label>Nombre Completo *</label>
                <input type="text" name="nombre" placeholder="Ingrese nombre completo" value="<?php echo htmlspecialchars($bailarin_data['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" id="emailInput" placeholder="correo@ejemplo.com" value="<?php echo htmlspecialchars($bailarin_data['email']); ?>" required>
                <small id="emailError" style="color: var(--danger-color); display: none;">Este email ya está registrado</small>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" placeholder="Número de teléfono" value="<?php echo htmlspecialchars($bailarin_data['telefono']); ?>">
            </div>
            <div class="form-group">
                <label>Género</label>
                <select name="genero">
                    <option value="M" <?php if($bailarin_data['genero'] == 'M') echo 'selected'; ?>>Masculino</option>
                    <option value="F" <?php if($bailarin_data['genero'] == 'F') echo 'selected'; ?>>Femenino</option>
                    <option value="Otro" <?php if($bailarin_data['genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                </select>
            </div>
            <?php if($edit_mode): ?>
            <div class="form-group">
                <label>Contraseña</label>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    <input type="password" value="bailarin123" disabled style="flex: 1; opacity: 0.7;">
                    <a href="admin.php?view=bailarines&reset_password=<?php echo $bailarin_data['id']; ?>" class="btn-submit" style="padding: 0.5rem 1rem; font-size: 0.75rem; text-decoration: none; background: var(--warning-color);">Resetear</a>
                </div>
                <small style="color: var(--text-muted);">La contraseña actual es: <strong>bailarin123</strong></small>
            </div>
            <?php endif; ?>
            <button type="submit" name="save_bailarin" class="btn-submit" style="width: 100%; margin-top: 1rem;">Guardar</button>
        </form>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div id="viewModal" class="modal">
    <div class="modal-content" style="max-width: 450px;">
        <span class="close-btn" onclick="closeViewModal()">&times;</span>
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div id="viewAvatar" style="width: 80px; height: 80px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600; margin: 0 auto 1rem;"></div>
            <h3 id="viewNombre" style="margin: 0 0 0.5rem;"></h3>
            <span id="viewBadge" class="badge"></span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--primary-bg); border-radius: 0.5rem;">
                <span style="color: var(--text-muted);">Email</span>
                <span id="viewEmail" style="font-weight: 500;"></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--primary-bg); border-radius: 0.5rem;">
                <span style="color: var(--text-muted);">Teléfono</span>
                <span id="viewTelefono" style="font-weight: 500;"></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--primary-bg); border-radius: 0.5rem;">
                <span style="color: var(--text-muted);">Fecha de Registro</span>
                <span id="viewFecha" style="font-weight: 500;"></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--primary-bg); border-radius: 0.5rem;">
                <span style="color: var(--text-muted);">Préstamos Activos</span>
                <span id="viewPrestamos" style="font-weight: 600; color: var(--accent-color);"></span>
            </div>
        </div>
        <div style="margin-top: 1.5rem; display: flex; gap: 0.5rem;">
            <a id="viewEditBtn" class="btn-submit" style="flex: 1; text-align: center; text-decoration: none;">Editar</a>
            <button onclick="closeViewModal()" class="btn-submit" style="flex: 1; background: #6b7280;">Cerrar</button>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('bailarinModal');
    const viewModal = document.getElementById('viewModal');
    const btn = document.getElementById('addBtn');

    function closeModal() {
        modal.classList.remove('active');
        <?php if($edit_mode): ?>
        setTimeout(() => window.location.href = 'admin.php?view=bailarines', 200);
        <?php endif; ?>
    }

    function closeViewModal() {
        viewModal.classList.remove('active');
    }

    btn.onclick = function() {
        <?php if($edit_mode): ?>
        window.location.href = 'admin.php?view=bailarines';
        <?php else: ?>
        modal.classList.add('active');
        <?php endif; ?>
    }

    // Close modals on backdrop click
    modal.onclick = function(event) {
        if (event.target === modal) closeModal();
    }
    viewModal.onclick = function(event) {
        if (event.target === viewModal) closeViewModal();
    }

    // Quick view function
    function viewBailarin(id, nombre, email, telefono, genero, fecha, prestamos) {
        document.getElementById('viewAvatar').textContent = nombre.charAt(0).toUpperCase();
        document.getElementById('viewNombre').textContent = nombre;
        document.getElementById('viewEmail').textContent = email;
        document.getElementById('viewTelefono').textContent = telefono || '-';
        document.getElementById('viewFecha').textContent = fecha || 'No disponible';
        document.getElementById('viewPrestamos').textContent = prestamos + ' activo' + (prestamos !== 1 ? 's' : '');
        
        const badge = document.getElementById('viewBadge');
        badge.className = 'badge ' + (genero === 'M' ? 'badge-male' : (genero === 'F' ? 'badge-female' : 'badge-other'));
        badge.textContent = genero === 'M' ? 'Masculino' : (genero === 'F' ? 'Femenino' : 'Otro');
        
        document.getElementById('viewEditBtn').href = 'admin.php?view=bailarines&edit=' + id;
        
        viewModal.classList.add('active');
    }

    // Form validation
    document.getElementById('bailarinForm')?.addEventListener('submit', function(e) {
        const emailInput = document.getElementById('emailInput');
        const emailError = document.getElementById('emailError');
        if (emailError && emailError.style.display === 'block') {
            e.preventDefault();
            emailInput.focus();
        }
    });
</script>

<?php if (count($bailarines) === 0): ?>
<div style="text-align: center; padding: 3rem; color: var(--text-muted);">
    <svg style="width: 64px; height: 64px; margin-bottom: 1rem; opacity: 0.5;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
    <p>No se encontraron bailarines</p>
    <button onclick="document.getElementById('addBtn').click()" class="btn-submit" style="margin-top: 1rem;">Agregar el primero</button>
</div>
<?php else: ?>

<table>
    <thead>
        <tr>
            <th style="cursor: pointer;" onclick="sortTable(0)">Bailarín ↕</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Género</th>
            <th>Préstamos</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bailarines as $b): ?>
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: var(--accent-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; cursor: pointer;" 
                             onclick="viewBailarin(<?php echo $b['id']; ?>, '<?php echo htmlspecialchars($b['nombre']); ?>', '<?php echo htmlspecialchars($b['email']); ?>', '<?php echo htmlspecialchars($b['telefono']); ?>', '<?php echo $b['genero']; ?>', '<?php echo isset($b['fecha_registro']) ? date('d/m/Y', strtotime($b['fecha_registro'])) : ''; ?>', <?php echo isset($active_loans[$b['id']]) ? $active_loans[$b['id']] : 0; ?>)"
                             title="Ver detalles">
                            <?php echo strtoupper(substr($b['nombre'], 0, 1)); ?>
                        </div>
                        <div>
                            <strong><?php echo htmlspecialchars($b['nombre']); ?></strong>
                        </div>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($b['email']); ?></td>
                <td><?php echo htmlspecialchars($b['telefono'] ?: '-'); ?></td>
                <td>
                    <?php 
                    $badge_class = $b['genero'] == 'M' ? 'badge-male' : ($b['genero'] == 'F' ? 'badge-female' : 'badge-other');
                    $badge_label = $b['genero'] == 'M' ? 'Masculino' : ($b['genero'] == 'F' ? 'Femenino' : 'Otro');
                    ?>
                    <span class="badge <?php echo $badge_class; ?>"><?php echo $badge_label; ?></span>
                </td>
                <td>
                    <?php 
                    $prestamos_count = isset($active_loans[$b['id']]) ? $active_loans[$b['id']] : 0;
                    if ($prestamos_count > 0): ?>
                        <span class="badge badge-approved"><?php echo $prestamos_count; ?> activo<?php echo $prestamos_count != 1 ? 's' : ''; ?></span>
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-size: 0.875rem;">-</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="admin.php?view=bailarines&edit=<?php echo $b['id']; ?>" class="action-btn btn-edit" title="Editar">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <a href="admin.php?view=bailarines&delete=<?php echo $b['id']; ?>" onclick="event.preventDefault(); if(confirm('¿Está seguro de eliminar este bailarín?')) { window.location.href=this.href; }" class="action-btn btn-delete" title="Eliminar">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
