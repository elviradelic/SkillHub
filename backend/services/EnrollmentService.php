<?php

require_once __DIR__ . '/../dao/EnrollmentDao.php';
require_once __DIR__ . '/../facades/EnrollmentFacade.php';

class EnrollmentService {
    private $dao;
    private $facade;

    public function __construct() {
        $this->dao = new EnrollmentDao();
        $this->facade = new EnrollmentFacade();
    }

    public function getEnrollments() {
        return $this->dao->getAllEnrollments();
    }

    public function createEnrollment($data) {
        return $this->facade->enrollStudent($data);
    }
}