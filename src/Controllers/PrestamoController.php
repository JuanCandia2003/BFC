<?php
/**
 * Prestamo Controller - Manejo de préstamos de vestuarios
 */

namespace Src\Controllers;

class PrestamoController extends Controller
{
    public function index($filter = 'all')
    {
        $sql = "SELECT p.*, b.nombre as bailarin, v.nombre as vestuario, f.nombre as funcion, v.imagen as v_imagen
                FROM prestamos p 
                JOIN bailarines b ON p.bailarin_id = b.id 
                JOIN vestuarios v ON p.vestuario_id = v.id 
                JOIN funciones f ON p.funcion_id = f.id";

        if ($filter !== 'all') {
            $sql .= " WHERE p.estado = ?";
            $stmt = $this->pdo->prepare($sql . " ORDER BY p.fecha_solicitud DESC");
            $stmt->execute([$filter]);
        } else {
            $stmt = $this->pdo->query($sql . " ORDER BY p.fecha_solicitud DESC");
        }

        return $stmt->fetchAll();
    }

    public function store()
    {
        $data = [
            'bailarin_id' => $_POST['bailarin_id'] ?? 0,
            'vestuario_id' => $_POST['vestuario_id'] ?? 0,
            'funcion_id' => $_POST['funcion_id'] ?? 0,
            'observaciones' => $_POST['observaciones'] ?? '',
            'estado' => 'pendiente'
        ];

        $stmt = $this->pdo->prepare("INSERT INTO prestamos (bailarin_id, vestuario_id, funcion_id, observaciones, estado, fecha_solicitud) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$data['bailarin_id'], $data['vestuario_id'], $data['funcion_id'], $data['observaciones'], $data['estado']]);
    }

    public function approve($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM prestamos WHERE id = ?");
        $stmt->execute([$id]);
        $prestamo = $stmt->fetch();

        if ($prestamo && $prestamo['estado'] === 'pendiente') {
            $update = $this->pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ? AND cantidad_disponible > 0");
            $update->execute([$prestamo['vestuario_id']]);

            if ($update->rowCount() > 0) {
                $this->pdo->prepare("UPDATE prestamos SET estado = 'aprobado' WHERE id = ?")->execute([$id]);
                return true;
            }
        }
        return false;
    }

    public function reject($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM prestamos WHERE id = ? AND estado = 'pendiente'");
        $stmt->execute([$id]);
        $prestamo = $stmt->fetch();

        if ($prestamo) {
            $this->pdo->prepare("UPDATE prestamos SET estado = 'rechazado' WHERE id = ?")->execute([$id]);
            return true;
        }
        return false;
    }

    public function returnVestuario($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM prestamos WHERE id = ?");
        $stmt->execute([$id]);
        $prestamo = $stmt->fetch();

        if ($prestamo && $prestamo['estado'] === 'aprobado') {
            $this->pdo->prepare("UPDATE vestuarios SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?")->execute([$prestamo['vestuario_id']]);
            $this->pdo->prepare("UPDATE prestamos SET estado = 'devuelto', fecha_devolucion = NOW() WHERE id = ?")->execute([$id]);
            return true;
        }
        return false;
    }

    public function getStats()
    {
        $stats = [];
        
        $stats['total'] = $this->pdo->query("SELECT COUNT(*) FROM prestamos")->fetchColumn();
        $stats['pendiente'] = $this->pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'pendiente'")->fetchColumn();
        $stats['aprobado'] = $this->pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'aprobado'")->fetchColumn();
        $stats['devuelto'] = $this->pdo->query("SELECT COUNT(*) FROM prestamos WHERE estado = 'devuelto'")->fetchColumn();
        
        return $stats;
    }
}
