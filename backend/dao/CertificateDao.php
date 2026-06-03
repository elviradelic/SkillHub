<?php

require_once __DIR__ . '/../config/database.php';

class CertificateDao {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createCertificate($data) {

        $code = strtoupper(
            uniqid(
                'CERT-'
            )
        );

        $query = "INSERT INTO certificates
                  (user_id, course_id, certificate_code)
                  VALUES
                  (:user_id, :course_id, :certificate_code)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':certificate_code' => $code
        ]);

        return [
            'id' => $this->conn->lastInsertId(),
            'certificate_code' => $code
        ];
    }

    public function getUserCertificates($userId) {

        $query = "SELECT
                    certificates.*,
                    courses.title AS course_title
                  FROM certificates
                  JOIN courses
                    ON certificates.course_id = courses.id
                  WHERE certificates.user_id = :user_id
                  ORDER BY certificates.issue_date DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function certificateExists($userId, $courseId) {

        $query = "SELECT *
                  FROM certificates
                  WHERE user_id = :user_id
                  AND course_id = :course_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $userId,
            ':course_id' => $courseId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}