<?php

require_once __DIR__ . '/../dao/CourseDao.php';

class CourseService {
    private $courseDao;

    public function __construct() {
        $this->courseDao = new CourseDao();
    }

    public function getCourses() {
        return $this->courseDao->getAllCourses();
    }

    public function getCoursesByInstructor($instructorId) {
        return $this->courseDao->getCoursesByInstructor($instructorId);
    }

    public function createCourse($data) {
        return $this->courseDao->createCourse($data);
    }

    public function getCourse($id) {
        return $this->courseDao->getCourseById($id);
    }

    public function updateCourse($id, $data) {
        return $this->courseDao->updateCourse($id, $data);
    }

    public function deleteCourse($id) {
        return $this->courseDao->deleteCourse($id);
    }
}