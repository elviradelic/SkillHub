<?php

require_once __DIR__ . '/../dao/LessonDao.php';

class LessonService {
    private $lessonDao;

    public function __construct() {
        $this->lessonDao = new LessonDao();
    }

    public function getLessons() {
        return $this->lessonDao->getAllLessons();
    }

    public function getLessonsByCourse($courseId) {
        return $this->lessonDao->getLessonsByCourse($courseId);
    }

    public function createLesson($data) {
        return $this->lessonDao->createLesson($data);
    }
}
