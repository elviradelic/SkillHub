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

    public function getQuizQuestions($quizId) {
        return $this->dao->getQuizQuestions($quizId);
    }

    public function submitQuizResult($data) {
        return $this->dao->submitQuizResult($data);
    }
    public function getUserResults($userId) {
    return $this->dao->getUserResults($userId);
}
}