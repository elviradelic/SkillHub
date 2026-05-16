<?php

require_once __DIR__ . '/../config/database.php';

class AuthDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}