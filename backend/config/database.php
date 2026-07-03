<?php

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;

    public function __construct()
    {
        $this->host = getenv("DB_HOST") ?: "";
        $this->db_name = getenv("DB_NAME") ?: "";
        $this->username = getenv("DB_USER") ?: "";
        $this->password = getenv("DB_PASSWORD") ?: "";
        $this->port = getenv("DB_PORT") ?: "";
    }

    public function connect()
    {
        try {
            $conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset=utf8mb4",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            echo json_encode([
                "error" => "Database connection failed",
                "details" => $e->getMessage()
            ]);
            return null;
        }
    }
}
