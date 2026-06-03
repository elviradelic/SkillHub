<?php

require_once __DIR__ . '/../services/QuizService.php';

class QuizController {

    private $service;

    public function __construct() {
        $this->service = new QuizService();
    }

    public function getQuizByCourse($courseId) {
        $quiz = $this->service->getQuizByCourse($courseId);

        echo json_encode([
            "success" => true,
            "data" => $quiz
        ]);
    }

    public function createQuiz() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->createQuiz($data);

        echo json_encode([
            "success" => true,
            "message" => "Quiz created",
            "id" => $id
        ]);
    }

    public function getQuizQuestions($quizId) {
        $questions = $this->service->getQuizQuestions($quizId);

        echo json_encode([
            "success" => true,
            "data" => $questions
        ]);
    }

    public function createQuestion() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->createQuestion($data);

        echo json_encode([
            "success" => true,
            "message" => "Question created",
            "id" => $id
        ]);
    }

    public function submitQuizResult() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->submitQuizResult($data);

        echo json_encode([
            "success" => true,
            "message" => "Quiz result saved",
            "id" => $id
        ]);
    }

    public function submitQuizWithAnswers() {
        $data = json_decode(file_get_contents("php://input"), true);

        $result = $this->service->submitQuizWithAnswers($data);

        echo json_encode([
            "success" => true,
            "data" => $result
        ]);
    }

    public function getUserResults($userId) {
        $results = $this->service->getUserResults($userId);

        echo json_encode([
            "success" => true,
            "data" => $results
        ]);
    }
}