<?php
/**
 * Clase Uploader - Manejo de Archivos Subidos
 * 
 * Gestiona la subida de archivos al servidor
 * con validaciones de seguridad.
 */

namespace Src\Helpers;

class Uploader {
    private $uploadPath;
    private $maxFileSize;
    private $allowedTypes;
    private $errors = [];

    /**
     * Constructor
     */
    public function __construct() {
        $this->uploadPath = defined('UPLOAD_PATH') ? UPLOAD_PATH : __DIR__ . '/../../assets/images/';
        $this->maxFileSize = defined('UPLOAD_MAX_SIZE') ? UPLOAD_MAX_SIZE : 5242880; // 5MB
        $this->allowedTypes = defined('UPLOAD_ALLOWED_TYPES') ? UPLOAD_ALLOWED_TYPES : [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp'
        ];
    }

    /**
     * Subir archivo
     */
    public function upload($file, $prefix = '') {
        $this->errors = [];

        // Validar que sea un archivo válido
        if (!isset($file['error']) || is_array($file['error'])) {
            $this->errors[] = 'Archivo inválido';
            return false;
        }

        // Verificar errores de PHP
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return false;
        }

        // Verificar tamaño
        if ($file['size'] > $this->maxFileSize) {
            $this->errors[] = 'El archivo excede el tamaño máximo permitido';
            return false;
        }

        // Verificar tipo MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $this->allowedTypes)) {
            $this->errors[] = 'Tipo de archivo no permitido';
            return false;
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . uniqid() . '_' . time() . '.' . $extension;

        //确保目录存在
        if (!is_dir($this->uploadPath)) {
            mkdir($this->uploadPath, 0755, true);
        }

        // Mover archivo
        $destination = $this->uploadPath . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->errors[] = 'Error al mover el archivo';
            return false;
        }

        return $filename;
    }

    /**
     * Subir múltiples archivos
     */
    public function uploadMultiple($files, $prefix = '') {
        $uploaded = [];
        
        foreach ($files['name'] as $key => $name) {
            $file = [
                'name' => $files['name'][$key],
                'type' => $files['type'][$key],
                'tmp_name' => $files['tmp_name'][$key],
                'error' => $files['error'][$key],
                'size' => $files['size'][$key]
            ];

            $result = $this->upload($file, $prefix);
            
            if ($result) {
                $uploaded[] = $result;
            }
        }

        return $uploaded;
    }

    /**
     * Eliminar archivo
     */
    public function delete($filename) {
        $filepath = $this->uploadPath . $filename;
        
        if (file_exists($filepath) && is_file($filepath)) {
            return unlink($filepath);
        }
        
        return false;
    }

    /**
     * Obtener errores
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Obtener último error
     */
    public function getLastError() {
        return empty($this->errors) ? null : $this->errors[0];
    }

    /**
     * Verificar si hay errores
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Obtener mensaje de error de subida
     */
    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'Archivo parcialmente subido',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en disco',
            UPLOAD_ERR_EXTENSION => 'Subida detenida por extensión'
        ];

        return $errors[$errorCode] ?? 'Error desconocido';
    }

    /**
     * Obtener ruta pública del archivo
     */
    public function getFileUrl($filename) {
        return 'assets/images/' . $filename;
    }
}

/**
 * Función helper para subir imagen
 */
function uploadImage($file) {
    $uploader = new Uploader();
    return $uploader->upload($file, 'img_');
}
