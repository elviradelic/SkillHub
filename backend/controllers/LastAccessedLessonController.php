<?php

require_once __DIR__ . '/../services/LastAccessedLessonService.php';

class LastAccessedLessonController {

    private $service;

    public function __construct() {
        $this->service = new LastAccessedLessonService();
    }

    public function saveLastAccessedLesson() {

        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $saved = $this->service->saveLastAccessedLesson($data);

        echo json_encode([
            "success" => $saved,
            "message" => $saved
                ? "Last lesson saved"
                : "Failed to save lesson"
        ]);
    }

    public function getLastAccessedLesson($userId, $courseId) {

        $lesson = $this->service->getLastAccessedLesson(
            $userId,
            $courseId
        );

        echo json_encode([
            "success" => true,
            "data" => $lesson
        ]);
    }
}