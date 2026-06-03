<?php

require_once __DIR__ . '/../dao/ProgressDao.php';

class ProgressService {

    private $progressDao;

    public function __construct() {
        $this->progressDao = new ProgressDao();
    }

    public function markLessonCompleted($data) {
        return $this->progressDao->markLessonCompleted($data);
    }

    public function getCourseProgress($userId, $courseId) {
        return $this->progressDao->getCourseProgress($userId, $courseId);
    }
}