<?php
/**
 * Database Connection - Clase para gestionar la conexión a la base de datos
 */

namespace Src\Database;

use PDO;
use PDOException;

class Connection
{
    private static $instance = null;
    private $pdo;

    private $host = 'db';
    private $dbname = 'BFC';
    private $username = 'usuario';
    private $password = 'password';
    private $charset = 'utf8mb4';

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    private function __clone() {}
    public function __wakeup() {
        throw new \Exception("Cannot unserialize singleton");
    }
}
