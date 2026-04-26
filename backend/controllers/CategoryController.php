<?php

require_once __DIR__ . '/../services/CategoryService.php';

class CategoryController {
    private $service;

    public function __construct() {
        $this->service = new CategoryService();
    }

    public function getCategories() {
        echo json_encode($this->service->getCategories());
    }

    public function createCategory() {
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $this->service->createCategory($data);

        echo json_encode([
            "message" => "Category created",
            "id" => $id
        ]);
    }
}