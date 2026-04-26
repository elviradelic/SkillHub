<?php

require_once __DIR__ . '/../config/database.php';

class CategoryDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllCategories() {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCategory($data) {
        $query = "INSERT INTO categories (name, description)
                  VALUES (:name, :description)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'] ?? null
        ]);

        return $this->conn->lastInsertId();
    }
}