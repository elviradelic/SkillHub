<?php

require_once __DIR__ . '/../dao/AuthDao.php';

class AuthService {
    private $authDao;

    public function __construct() {
        $this->authDao = new AuthDao();
    }

    public function login($email, $password) {

        $user = $this->authDao->findUserByEmail($email);

        if (!$user) {
            return [
                "success" => false,
                "message" => "User not found"
            ];
        }

        if (!password_verify($password, $user['password'])) {
            return [
                "success" => false,
                "message" => "Invalid password"
            ];
        }

        return [
            "success" => true,
            "message" => "Login successful",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];
    }
}