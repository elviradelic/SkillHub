<?php

require_once __DIR__ . '/../config/database.php';

class EnrollmentDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllEnrollments() {
        $query = "SELECT * FROM enrollments";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createEnrollment($data) {
        $query = "INSERT INTO enrollments (user_id, course_id, status)
                  VALUES (:user_id, :course_id, :status)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':status' => 'active'
        ]);

        return $this->conn->lastInsertId();
    }
    public function findEnrollment($userId, $courseId) {
    $query = "SELECT * FROM enrollments 
              WHERE user_id = :user_id AND course_id = :course_id";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
        ':user_id' => $userId,
        ':course_id' => $courseId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}