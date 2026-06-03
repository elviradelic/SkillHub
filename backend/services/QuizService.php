<?php

require_once __DIR__ . '/../dao/QuizDao.php';

class QuizService {

    private $dao;

    public function __construct() {
        $this->dao = new QuizDao();
    }

    public function getQuizByCourse($courseId) {
        return $this->dao->getQuizByCourse($courseId);
    }

    public function createQuiz($data) {
        return $this->dao->createQuiz($data);
    }

    public function getQuizQuestions($quizId) {
        return $this->dao->getQuizQuestions($quizId);
    }

    public function createQuestion($data) {
        return $this->dao->createQuestion($data);
    }

    public function submitQuizResult($data) {
        return $this->dao->submitQuizResult($data);
    }

    public function submitQuizWithAnswers($data) {
        return $this->dao->submitQuizWithAnswers($data);
    }

    public function getUserResults($userId) {
        return $this->dao->getUserResults($userId);
    }
}