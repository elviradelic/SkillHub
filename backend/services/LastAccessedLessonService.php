<?php

require_once __DIR__ . '/../dao/LastAccessedLessonDao.php';

class LastAccessedLessonService {

    private $dao;

    public function __construct() {
        $this->dao = new LastAccessedLessonDao();
    }

    public function saveLastAccessedLesson($data) {
        return $this->dao->saveLastAccessedLesson($data);
    }

    public function getLastAccessedLesson($userId, $courseId) {
        return $this->dao->getLastAccessedLesson($userId, $courseId);
    }
}