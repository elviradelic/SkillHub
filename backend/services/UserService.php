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
        // možeš kasnije dodati validaciju
        return $this->userDao->createUser($data);
    }
}