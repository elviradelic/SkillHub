<?php

require_once __DIR__ . '/../dao/CertificateDao.php';

class CertificateService {

    private $certificateDao;

    public function __construct() {
        $this->certificateDao = new CertificateDao();
    }

    public function createCertificate($data) {
        $existingCertificate = $this->certificateDao->certificateExists(
            $data['user_id'],
            $data['course_id']
        );

        if ($existingCertificate) {
            return [
                "already_exists" => true,
                "certificate" => $existingCertificate
            ];
        }

        return [
            "already_exists" => false,
            "certificate" => $this->certificateDao->createCertificate($data)
        ];
    }

    public function getUserCertificates($userId) {
        return $this->certificateDao->getUserCertificates($userId);
    }
}