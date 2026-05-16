<?php

require_once __DIR__ . '/../services/AuthService.php';

class AuthController {
    private $service;

    public function __construct() {
        $this->service = new AuthService();
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            echo json_encode([
                "success" => false,
                "message" => "Email and password are required"
            ]);
            return;
        }

        $result = $this->service->login($data['email'], $data['password']);

        echo json_encode($result);
    }
}