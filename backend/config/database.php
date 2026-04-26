<?php

class Database {
    private $host = "localhost";
    private $db_name = "skillhub";
    private $username = "root";
    private $password = "";

    public function connect() {
        try {
            $conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
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