<?php

require_once __DIR__ . '/../services/EnrollmentService.php';

class EnrollmentController {
    private $service;

    public function __construct() {
        $this->service = new EnrollmentService();
    }

    public function getEnrollments() {
        echo json_encode($this->service->getEnrollments());
    }

   public function createEnrollment() {
    $data = json_decode(file_get_contents("php://input"), true);

    $result = $this->service->createEnrollment($data);

    echo json_encode($result);
}
public function getUserEnrollments($userId) {

    $enrollments = $this->service->getUserEnrollments($userId);

    echo json_encode([
        "success" => true,
        "data" => $enrollments
    ]);
}
}