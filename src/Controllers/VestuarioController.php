<?php
/**
 * Vestuario Controller - Manejo de vestuarios
 */

namespace Src\Controllers;

class VestuarioController extends Controller
{
    public function index()
    {
        $filter = $_GET['filter'] ?? 'all';
        
        $sql = "SELECT v.*, 
                (SELECT COUNT(*) FROM prestamos p WHERE p.vestuario_id = v.id AND p.estado = 'aprobado') as prestamos_activos
                FROM vestuarios v";

        if ($filter === 'available') {
            $sql .= " WHERE v.cantidad_disponible > 0";
        } elseif ($filter === 'unavailable') {
            $sql .= " WHERE v.cantidad_disponible = 0";
        }

        $stmt = $this->pdo->query($sql . " ORDER BY v.nombre");
        return $stmt->fetchAll();
    }

    public function store()
    {
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'talla' => $_POST['talla'] ?? '',
            'cantidad_total' => (int)($_POST['cantidad_total'] ?? 1),
            'cantidad_disponible' => (int)($_POST['cantidad_total'] ?? 1),
            'imagen' => $_POST['imagen_actual'] ?? ''
        ];

        if (!empty($_FILES['imagen']['name'])) {
            $uploader = new \Src\Helpers\Uploader();
            $data['imagen'] = $uploader->upload($_FILES['imagen'], 'vestuario_');
        }

        $stmt = $this->pdo->prepare("INSERT INTO vestuarios (nombre, descripcion, talla, cantidad_total, cantidad_disponible, imagen) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['nombre'], $data['descripcion'], $data['talla'], $data['cantidad_total'], $data['cantidad_disponible'], $data['imagen']]);
    }

    public function update($id)
    {
        $stmt = $this->pdo->prepare("SELECT imagen FROM vestuarios WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch();

        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? '',
            'talla' => $_POST['talla'] ?? '',
            'cantidad_total' => (int)($_POST['cantidad_total'] ?? 1),
            'imagen' => $current['imagen'] ?? ''
        ];

        if (!empty($_FILES['imagen']['name'])) {
            $uploader = new \Src\Helpers\Uploader();
            $newImage = $uploader->upload($_FILES['imagen'], 'vestuario_');
            if ($newImage) {
                $data['imagen'] = $newImage;
            }
        }

        $stmt = $this->pdo->prepare("UPDATE vestuarios SET nombre = ?, descripcion = ?, talla = ?, cantidad_total = ?, imagen = ? WHERE id = ?");
        return $stmt->execute([$data['nombre'], $data['descripcion'], $data['talla'], $data['cantidad_total'], $data['imagen'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("SELECT imagen FROM vestuarios WHERE id = ?");
        $stmt->execute([$id]);
        $vestuario = $stmt->fetch();

        if ($vestuario && !empty($vestuario['imagen'])) {
            $uploader = new \Src\Helpers\Uploader();
            $uploader->delete($vestuario['imagen']);
        }

        $stmt = $this->pdo->prepare("DELETE FROM vestuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAvailable()
    {
        $stmt = $this->pdo->query("SELECT * FROM vestuarios WHERE cantidad_disponible > 0 ORDER BY nombre");
        return $stmt->fetchAll();
    }
}
