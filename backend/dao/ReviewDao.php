<?php

require_once __DIR__ . '/../config/database.php';

class ReviewDao {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createReview($data) {

        $query = "INSERT INTO reviews 
                  (user_id, course_id, rating, comment)
                  VALUES
                  (:user_id, :course_id, :rating, :comment)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':rating' => $data['rating'],
            ':comment' => $data['comment']
        ]);

        return $this->conn->lastInsertId();
    }

    public function getCourseReviews($courseId) {

        $query = "SELECT 
                    reviews.id,
                    users.name,
                    reviews.rating,
                    reviews.comment,
                    reviews.created_at
                  FROM reviews
                  JOIN users
                  ON reviews.user_id = users.id
                  WHERE reviews.course_id = :course_id
                  ORDER BY reviews.created_at DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':course_id' => $courseId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}