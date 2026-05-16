<?php

require_once __DIR__ . '/../dao/ReviewDao.php';

class ReviewService {

    private $dao;

    public function __construct() {
        $this->dao = new ReviewDao();
    }

    public function createReview($data) {
        return $this->dao->createReview($data);
    }

    public function getCourseReviews($courseId) {
        return $this->dao->getCourseReviews($courseId);
    }
}