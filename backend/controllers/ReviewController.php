<?php

require_once __DIR__ . '/../services/ReviewService.php';

class ReviewController {

    private $service;

    public function __construct() {
        $this->service = new ReviewService();
    }

    public function createReview() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->createReview($data);

        echo json_encode([
            "success" => true,
            "message" => "Review created",
            "id" => $id
        ]);
    }

    public function getCourseReviews($courseId) {
        $reviews = $this->service->getCourseReviews($courseId);

        echo json_encode([
            "success" => true,
            "data" => $reviews
        ]);
    }
}