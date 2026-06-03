<?php

require_once __DIR__ . '/../dao/UserDao.php';

class UserService {

    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao();
    }

    public function getUsers() {
        return $this->userDao->getAllUsers();
    }

    public function createUser($data) {
        return $this->userDao->createUser($data);
    }

    public function deleteUser($id) {
        return $this->userDao->deleteUser($id);
    }

    public function promoteToInstructor($id) {
        return $this->userDao->promoteToInstructor($id);
    }
}