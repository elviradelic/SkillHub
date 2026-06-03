<?php

require_once __DIR__ . '/../services/CertificateService.php';

class CertificateController {

    private $service;

    public function __construct() {
        $this->service = new CertificateService();
    }

    public function createCertificate() {
        $data = json_decode(file_get_contents("php://input"), true);

        $result = $this->service->createCertificate($data);

        echo json_encode([
            "success" => true,
            "message" => $result["already_exists"]
                ? "Certificate already exists"
                : "Certificate generated successfully",
            "data" => $result["certificate"]
        ]);
    }

    public function getUserCertificates($userId) {
        $certificates = $this->service->getUserCertificates($userId);

        echo json_encode([
            "success" => true,
            "data" => $certificates
        ]);
    }
}