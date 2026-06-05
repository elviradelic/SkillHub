<?php

require_once __DIR__ . '/../config/database.php';

class ProgressDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function markLessonCompleted($data) {
        $query = "INSERT INTO progress (user_id, course_id, lesson_id, completed)
                  VALUES (:user_id, :course_id, :lesson_id, 1)
                  ON DUPLICATE KEY UPDATE 
                    completed = 1,
                    completed_at = CURRENT_TIMESTAMP";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':lesson_id' => $data['lesson_id']
        ]);
    }

    public function getCourseProgress($userId, $courseId) {
        $query = "SELECT 
                    COUNT(DISTINCT lessons.id) AS total_lessons,
                    COUNT(DISTINCT progress.lesson_id) AS completed_lessons
                  FROM lessons
                  LEFT JOIN progress 
                    ON lessons.id = progress.lesson_id
                    AND progress.user_id = :user_id
                    AND progress.course_id = :course_id
                    AND progress.completed = 1
                  WHERE lessons.course_id = :course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $userId,
            ':course_id' => $courseId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $total = (int)$result['total_lessons'];
        $completed = (int)$result['completed_lessons'];

        $percentage = $total > 0
            ? round(($completed / $total) * 100, 2)
            : 0;

        return [
            "total_lessons" => $total,
            "completed_lessons" => $completed,
            "progress_percentage" => $percentage
        ];
    }
}