<?php

require_once __DIR__ . '/../config/database.php';

class CourseDao {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAllCourses() {
        $query = "SELECT 
                    courses.*, 
                    users.name AS instructor_name, 
                    categories.name AS category_name
                  FROM courses
                  LEFT JOIN users ON courses.instructor_id = users.id
                  LEFT JOIN categories ON courses.category_id = categories.id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursesByInstructor($instructorId) {
        $query = "SELECT 
                    courses.*, 
                    users.name AS instructor_name, 
                    categories.name AS category_name
                  FROM courses
                  LEFT JOIN users ON courses.instructor_id = users.id
                  LEFT JOIN categories ON courses.category_id = categories.id
                  WHERE courses.instructor_id = :instructor_id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':instructor_id' => $instructorId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createCourse($data) {
        $query = "INSERT INTO courses 
                  (title, description, price, instructor_id, category_id)
                  VALUES 
                  (:title, :description, :price, :instructor_id, :category_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':instructor_id' => $data['instructor_id'],
            ':category_id' => $data['category_id']
        ]);

        return $this->conn->lastInsertId();
    }

    public function getCourseById($id) {
        $query = "SELECT 
                    courses.*, 
                    users.name AS instructor_name, 
                    categories.name AS category_name
                  FROM courses
                  LEFT JOIN users ON courses.instructor_id = users.id
                  LEFT JOIN categories ON courses.category_id = categories.id
                  WHERE courses.id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateCourse($id, $data) {
        $query = "UPDATE courses 
                  SET 
                    title = :title, 
                    description = :description, 
                    price = :price, 
                    instructor_id = :instructor_id, 
                    category_id = :category_id
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':price' => $data['price'],
            ':instructor_id' => $data['instructor_id'],
            ':category_id' => $data['category_id']
        ]);
    }

    public function deleteCourse($id) {
        $query = "DELETE FROM courses WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}