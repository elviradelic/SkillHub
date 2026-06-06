<?php

require_once __DIR__ . '/../services/MaterialService.php';

class MaterialController {

    private $service;

    public function __construct() {
        $this->service = new MaterialService();
    }

    public function uploadMaterial() {
        if (
            !isset($_POST['course_id']) ||
            !isset($_POST['title']) ||
            !isset($_FILES['file'])
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required fields"
            ]);
            return;
        }

        $uploadDir = __DIR__ . '/../uploads/';

        $fileName = time() . '_' . basename($_FILES['file']['name']);
        $targetPath = $uploadDir . $fileName;

        if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
}

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            $id = $this->service->createMaterial([
                "course_id" => $_POST['course_id'],
                "title" => $_POST['title'],
                "file_path" => "uploads/" . $fileName
            ]);

            echo json_encode([
                "success" => true,
                "message" => "Material uploaded successfully",
                "id" => $id
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "File upload failed"
            ]);
        }
    }

    public function getMaterialsByCourse($courseId) {
        $materials = $this->service->getMaterialsByCourse($courseId);

        echo json_encode([
            "success" => true,
            "data" => $materials
        ]);
    }

    public function downloadMaterial($id) {
        $material = $this->service->getMaterialById($id);

        if (!$material) {
            http_response_code(404);
            echo json_encode([
                "success" => false,
                "message" => "Material not found"
            ]);
            return;
        }

        $filePath = __DIR__ . '/../' . $material['file_path'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            echo json_encode([
              "success" => false,
               "message" => "File upload failed",
               "upload_dir" => $uploadDir,
               "target_path" => $targetPath,
               "error" => $_FILES['file']['error']
            ]);;
            return;
        }

        $fileName = basename($filePath);

        header("Content-Description: File Transfer");
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"" . $fileName . "\"");
        header("Content-Length: " . filesize($filePath));
        header("Pragma: public");
        header("Cache-Control: must-revalidate");

        readfile($filePath);
        exit;
    }
}