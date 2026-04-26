<?php

require_once __DIR__ . '/../services/CourseService.php';

class CourseController {
    private $service;

    public function __construct() {
        $this->service = new CourseService();
    }

    public function getCourses() {
        echo json_encode($this->service->getCourses());
    }

    public function createCourse() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->createCourse($data);

        echo json_encode([
            "message" => "Course created",
            "id" => $id
        ]);
    }
    public function getCourseById($id) {
    $course = $this->service->getCourse($id);

    if ($course) {
        echo json_encode($course);
    } else {
        echo json_encode([
            "message" => "Course not found"
        ]);
    }
}

public function updateCourse($id) {
    $data = json_decode(file_get_contents("php://input"), true);

    $updated = $this->service->updateCourse($id, $data);

    echo json_encode([
        "message" => $updated ? "Course updated" : "Course update failed"
    ]);
}

public function deleteCourse($id) {
    $deleted = $this->service->deleteCourse($id);

    echo json_encode([
        "message" => $deleted ? "Course deleted" : "Course delete failed"
    ]);
}
}