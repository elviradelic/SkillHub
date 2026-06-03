<?php

require_once __DIR__ . '/../services/ProgressService.php';

class ProgressController {

    private $service;

    public function __construct() {
        $this->service = new ProgressService();
    }

    public function markLessonCompleted() {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $completed = $this->service->markLessonCompleted($data);

        echo json_encode([
            "success" => $completed,
            "message" => $completed
                ? "Lesson marked as completed"
                : "Failed to save progress"
        ]);
    }

    public function getCourseProgress($userId, $courseId) {
        $progress = $this->service->getCourseProgress(
            $userId,
            $courseId
        );

        echo json_encode([
            "success" => true,
            "data" => $progress
        ]);
    }
}