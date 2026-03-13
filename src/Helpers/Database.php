<?php
/**
 * Clase Database - Manejo de Conexión a MySQL
 * 
 * Implementa el patrón Singleton para garantizar
 * una única instancia de conexión.
 */

class Database {
    private static $instance = null;
    private $connection;
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;

    /**
     * Constructor privado - Patrón Singleton
     */
    private function __construct() {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET;

        $this->connect();
    }

    /**
     * Obtener instancia única de la clase
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establecer conexión a la base de datos
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];

            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new Exception("Error de conexión: " . $e->getMessage());
        }
    }

    /**
     * Obtener la conexión PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Ejecutar una consulta SQL
     */
    public function query($sql, $params = []) {
        try {
            if (empty($params)) {
                return $this->connection->query($sql);
            }

            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Error en consulta: " . $e->getMessage());
        }
    }

    /**
     * Obtener una fila como array asociativo
     */
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Obtener todas las filas como array
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Obtener el valor de una columna
     */
    public function fetchColumn($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * Insertar registro y obtener ID
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);

        return $this->connection->lastInsertId();
    }

    /**
     * Actualizar registros
     */
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $set);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        $params = array_merge($data, $whereParams);
        $stmt = $this->connection->prepare($sql);
        
        return $stmt->execute($params);
    }

    /**
     * Eliminar registros
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Iniciar transacción
     */
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    /**
     * Confirmar transacción
     */
    public function commit() {
        return $this->connection->commit();
    }

    /**
     * Revertir transacción
     */
    public function rollBack() {
        return $this->connection->rollBack();
    }

    /**
     * Verificar si existe registro
     */
    public function exists($table, $where, $params = []) {
        $sql = "SELECT 1 FROM {$table} WHERE {$where} LIMIT 1";
        return (bool) $this->fetchColumn($sql, $params);
    }

    /**
     * Contar registros
     */
    public function count($table, $where = '1=1', $params = []) {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
        return (int) $this->fetchColumn($sql, $params);
    }

    // Previene clonación del objeto
    private function __clone() {}

    // Previene deserialización del objeto
    public function __wakeup() {
        throw new Exception("No se puede deserializar Singleton");
    }
}

/**
 * Función helper para acceso rápido a la base de datos
 */
function db() {
    return Database::getInstance();
}
