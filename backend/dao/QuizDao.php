<?php

require_once __DIR__ . '/../config/database.php';

class QuizDao {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getQuizByCourse($courseId) {

        $query = "SELECT * FROM quizzes
                  WHERE course_id = :course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':course_id' => $courseId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getQuizQuestions($quizId) {

        $query = "SELECT 
                    id,
                    text,
                    points
                  FROM questions
                  WHERE quiz_id = :quiz_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':quiz_id' => $quizId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function submitQuizResult($data) {

        $query = "INSERT INTO results
                  (user_id, quiz_id, score)
                  VALUES
                  (:user_id, :quiz_id, :score)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':quiz_id' => $data['quiz_id'],
            ':score' => $data['score']
        ]);

        return $this->conn->lastInsertId();
    }
    public function getUserResults($userId) {
    $query = "SELECT 
                results.id,
                results.score,
                results.date_taken,
                quizzes.title AS quiz_title,
                courses.title AS course_title
              FROM results
              JOIN quizzes ON results.quiz_id = quizzes.id
              JOIN courses ON quizzes.course_id = courses.id
              WHERE results.user_id = :user_id
              ORDER BY results.date_taken DESC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute([
        ':user_id' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}