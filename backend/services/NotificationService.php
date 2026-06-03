<?php

require_once __DIR__ . '/../dao/NotificationDao.php';

class NotificationService {

    private $dao;

    public function __construct() {
        $this->dao = new NotificationDao();
    }

    public function createNotification($data) {
        return $this->dao->createNotification($data);
    }

    public function getUserNotifications($userId) {
        return $this->dao->getUserNotifications($userId);
    }
}