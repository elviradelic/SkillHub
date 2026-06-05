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

    public function createQuiz($data) {
        $query = "INSERT INTO quizzes
                  (course_id, title, duration)
                  VALUES
                  (:course_id, :title, :duration)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':course_id' => $data['course_id'],
            ':title' => $data['title'],
            ':duration' => $data['duration']
        ]);

        return $this->conn->lastInsertId();
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

    public function createQuestion($data) {
        $query = "INSERT INTO questions
                  (quiz_id, text, points, correct_answer)
                  VALUES
                  (:quiz_id, :text, :points, :correct_answer)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':quiz_id' => $data['quiz_id'],
            ':text' => $data['text'],
            ':points' => $data['points'],
            ':correct_answer' => $data['correct_answer']
        ]);

        return $this->conn->lastInsertId();
    }

    public function getQuestionsWithAnswers($quizId) {
        $query = "SELECT 
                    id,
                    text,
                    points,
                    correct_answer
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

    public function submitQuizWithAnswers($data) {
        $quizId = $data['quiz_id'];
        $userAnswers = $data['answers'];

        $questions = $this->getQuestionsWithAnswers($quizId);

        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($questions as $question) {
            $questionId = $question['id'];
            $points = (int)$question['points'];
            $correctAnswer = strtolower(trim($question['correct_answer']));

            $totalPoints += $points;

            if (isset($userAnswers[$questionId])) {
                $studentAnswer = strtolower(trim($userAnswers[$questionId]));

                if ($studentAnswer === $correctAnswer) {
                    $earnedPoints += $points;
                }
            }
        }

        $score = $totalPoints > 0
            ? round(($earnedPoints / $totalPoints) * 100, 2)
            : 0;

        $resultId = $this->submitQuizResult([
            'user_id' => $data['user_id'],
            'quiz_id' => $quizId,
            'score' => $score
        ]);

        return [
            "result_id" => $resultId,
            "score" => $score,
            "earned_points" => $earnedPoints,
            "total_points" => $totalPoints
        ];
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