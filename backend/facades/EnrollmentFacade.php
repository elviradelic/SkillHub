<?php

require_once __DIR__ . '/../dao/EnrollmentDao.php';

class EnrollmentFacade {
    private $enrollmentDao;

    public function __construct() {
        $this->enrollmentDao = new EnrollmentDao();
    }

    public function enrollStudent($data) {
        $existingEnrollment = $this->enrollmentDao->findEnrollment(
            $data['user_id'],
            $data['course_id']
        );

        if ($existingEnrollment) {
            return [
                "success" => false,
                "message" => "User is already enrolled in this course"
            ];
        }

        $id = $this->enrollmentDao->createEnrollment($data);

        return [
            "success" => true,
            "message" => "Enrollment created",
            "id" => $id
        ];
    }
}