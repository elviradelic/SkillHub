<?php

require_once __DIR__ . '/../services/NotificationService.php';

class NotificationController {

    private $service;

    public function __construct() {
        $this->service = new NotificationService();
    }

    public function createNotification() {
        $data = json_decode(file_get_contents("php://input"), true);

        $created = $this->service->createNotification($data);

        echo json_encode([
            "success" => $created,
            "message" => $created
                ? "Notification created"
                : "Failed to create notification"
        ]);
    }

    public function getUserNotifications($userId) {
        $notifications = $this->service->getUserNotifications($userId);

        echo json_encode([
            "success" => true,
            "data" => $notifications
        ]);
    }
}