<?php

require_once __DIR__ . '/../services/LessonService.php';

class LessonController {

    private $service;

    public function __construct() {
        $this->service = new LessonService();
    }

    public function getLessons() {
        echo json_encode(
            $this->service->getLessons()
        );
    }

    public function getLessonsByCourse($courseId) {
        echo json_encode(
            $this->service->getLessonsByCourse($courseId)
        );
    }

    public function createLesson() {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $id = $this->service->createLesson($data);

        echo json_encode([
            "message" => "Lesson created",
            "id" => $id
        ]);
    }

    public function updateLesson($id) {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $updated = $this->service->updateLesson(
            $id,
            $data
        );

        echo json_encode([
            "message" => $updated
                ? "Lesson updated"
                : "Lesson update failed"
        ]);
    }

    public function deleteLesson($id) {
        $deleted = $this->service->deleteLesson($id);

        echo json_encode([
            "message" => $deleted
                ? "Lesson deleted"
                : "Lesson delete failed"
        ]);
    }
}