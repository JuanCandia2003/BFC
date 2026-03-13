<?php
/**
 * Bailarin Controller - Manejo de bailarines
 */

namespace Src\Controllers;

class BailarinController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $stmt = $this->pdo->prepare("SELECT * FROM bailarines WHERE nombre LIKE ? OR email LIKE ? ORDER BY nombre");
            $stmt->execute(["%$search%", "%$search%"]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM bailarines ORDER BY nombre");
        }
        
        return $stmt->fetchAll();
    }

    public function store()
    {
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => password_hash($_POST['password'] ?? 'bailarin123', PASSWORD_DEFAULT),
            'telefono' => $_POST['telefono'] ?? '',
            'genero' => $_POST['genero'] ?? 'otro',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        $stmt = $this->pdo->prepare("INSERT INTO bailarines (nombre, email, password, telefono, genero, activo) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$data['nombre'], $data['email'], $data['password'], $data['telefono'], $data['genero'], $data['activo']]);
    }

    public function update($id)
    {
        $data = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'genero' => $_POST['genero'] ?? 'otro',
            'activo' => isset($_POST['activo']) ? 1 : 0
        ];

        $sql = "UPDATE bailarines SET nombre = ?, email = ?, telefono = ?, genero = ?, activo = ?";
        $params = [$data['nombre'], $data['email'], $data['telefono'], $data['genero'], $data['activo']];
        
        if (!empty($_POST['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM bailarines WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function resetPassword($id)
    {
        $newPassword = password_hash('bailarin123', PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE bailarines SET password = ? WHERE id = ?");
        return $stmt->execute([$newPassword, $id]);
    }

    public function getPrestamos($bailarinId)
    {
        $stmt = $this->pdo->prepare("SELECT p.*, v.nombre as vestuario, f.nombre as funcion 
            FROM prestamos p 
            JOIN vestuarios v ON p.vestuario_id = v.id 
            JOIN funciones f ON p.funcion_id = f.id 
            WHERE p.bailarin_id = ? 
            ORDER BY p.fecha_solicitud DESC");
        $stmt->execute([$bailarinId]);
        return $stmt->fetchAll();
    }
}
