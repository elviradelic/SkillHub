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

    public function getQuizQuestions($quizId) {
        $questions = $this->service->getQuizQuestions($quizId);

        echo json_encode([
            "success" => true,
            "data" => $questions
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
    public function getUserResults($userId) {
    $results = $this->service->getUserResults($userId);

    echo json_encode([
        "success" => true,
        "data" => $results
    ]);
}
}