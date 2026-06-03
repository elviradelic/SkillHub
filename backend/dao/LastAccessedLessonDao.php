<?php

require_once __DIR__ . '/../config/database.php';

class LastAccessedLessonDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function saveLastAccessedLesson($data) {
        $query = "INSERT INTO last_accessed_lessons 
                  (user_id, course_id, lesson_id)
                  VALUES 
                  (:user_id, :course_id, :lesson_id)
                  ON DUPLICATE KEY UPDATE 
                    lesson_id = :lesson_id,
                    accessed_at = CURRENT_TIMESTAMP";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':lesson_id' => $data['lesson_id']
        ]);
    }

    public function getLastAccessedLesson($userId, $courseId) {
        $query = "SELECT 
                    last_accessed_lessons.lesson_id,
                    last_accessed_lessons.accessed_at,
                    lessons.title,
                    lessons.content
                  FROM last_accessed_lessons
                  JOIN lessons 
                    ON last_accessed_lessons.lesson_id = lessons.id
                  WHERE last_accessed_lessons.user_id = :user_id
                  AND last_accessed_lessons.course_id = :course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $userId,
            ':course_id' => $courseId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}