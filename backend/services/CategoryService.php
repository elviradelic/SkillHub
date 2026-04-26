<?php

require_once __DIR__ . '/../dao/CategoryDao.php';

class CategoryService {
    private $categoryDao;

    public function __construct() {
        $this->categoryDao = new CategoryDao();
    }

    public function getCategories() {
        return $this->categoryDao->getAllCategories();
    }

    public function createCategory($data) {
        return $this->categoryDao->createCategory($data);
    }
}