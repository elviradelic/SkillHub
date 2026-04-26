<?php

require_once __DIR__ . '/../config/database.php';

class LessonDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllLessons() {
        $stmt = $this->conn->prepare("SELECT * FROM lessons ORDER BY lesson_order ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLessonsByCourse($courseId) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY lesson_order ASC"
        );
        $stmt->execute([':course_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLesson($data) {
        $query = "INSERT INTO lessons (course_id, title, content, lesson_order)
                  VALUES (:course_id, :title, :content, :lesson_order)";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':course_id' => $data['course_id'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':lesson_order' => $data['lesson_order']
        ]);

        return $this->conn->lastInsertId();
    }
}