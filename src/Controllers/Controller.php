<?php
/**
 * Base Controller - Clase padre para todos los controladores
 */

namespace Src\Controllers;

class Controller
{
    protected $pdo;
    protected $data = [];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = SRC_PATH . '/Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "Vista no encontrada: $view";
        }
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function sanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars($data ?? '', ENT_QUOTES, 'UTF-8');
    }

    protected function validate($rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $_POST[$field] ?? $_GET[$field] ?? null;
            
            if ($rule['required'] && empty($value)) {
                $errors[$field] = "El campo {$field} es requerido";
                continue;
            }
            
            if (!empty($value)) {
                if (isset($rule['email']) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "El campo {$field} debe ser un email válido";
                }
                
                if (isset($rule['min']) && strlen($value) < $rule['min']) {
                    $errors[$field] = "El campo {$field} debe tener al menos {$rule['min']} caracteres";
                }
                
                if (isset($rule['max']) && strlen($value) > $rule['max']) {
                    $errors[$field] = "El campo {$field} debe tener máximo {$rule['max']} caracteres";
                }
            }
        }
        
        return $errors;
    }
}
