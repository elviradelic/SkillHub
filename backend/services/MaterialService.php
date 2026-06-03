<?php

require_once __DIR__ . '/../dao/MaterialDao.php';

class MaterialService {

    private $dao;

    public function __construct() {
        $this->dao = new MaterialDao();
    }

    public function createMaterial($data) {
        return $this->dao->createMaterial($data);
    }

    public function getMaterialsByCourse($courseId) {
        return $this->dao->getMaterialsByCourse($courseId);
    }

    public function getMaterialById($id) {
        return $this->dao->getMaterialById($id);
    }
}