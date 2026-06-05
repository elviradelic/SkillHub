<?php

require_once __DIR__ . '/../config/database.php';

class UserDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllUsers() {
        $query = "SELECT id, name, email, role, created_at FROM users";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createUser($data) {
        $query = "INSERT INTO users (name, email, password, role) 
                  VALUES (:name, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $stmt->execute([
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':password' => $hashedPassword,
            ':role' => $data['role']
        ]);

        return $this->conn->lastInsertId();
    }

    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function promoteToInstructor($id) {
        $query = "UPDATE users 
                  SET role = 'instructor'
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}