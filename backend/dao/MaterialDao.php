<?php

require_once __DIR__ . '/../config/database.php';

class MaterialDao {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createMaterial($data) {

        $query = "INSERT INTO materials
                  (course_id, title, file_path)
                  VALUES
                  (:course_id, :title, :file_path)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':course_id' => $data['course_id'],
            ':title' => $data['title'],
            ':file_path' => $data['file_path']
        ]);

        return $this->conn->lastInsertId();
    }

    public function getMaterialsByCourse($courseId) {

        $query = "SELECT *
                  FROM materials
                  WHERE course_id = :course_id
                  ORDER BY uploaded_at DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':course_id' => $courseId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaterialById($id) {

        $query = "SELECT *
                  FROM materials
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}