<?php

require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $service;

    public function __construct() {
        $this->service = new UserService();
    }

    public function getUsers() {
        echo json_encode($this->service->getUsers());
    }

    public function createUser() {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $this->service->createUser($data);

        echo json_encode([
            "message" => "User created",
            "id" => $id
        ]);
    }

    public function deleteUser($id) {
        $deleted = $this->service->deleteUser($id);

        echo json_encode([
            "message" => $deleted
                ? "User deleted"
                : "User delete failed"
        ]);
    }

    public function promoteToInstructor($id) {
        $updated = $this->service->promoteToInstructor($id);

        echo json_encode([
            "message" => $updated
                ? "User promoted to instructor"
                : "User promotion failed"
        ]);
    }
}