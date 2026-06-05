<?php

require_once __DIR__ . '/../config/database.php';

class NotificationDao {

    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();

        if (!$this->conn) {
            throw new Exception("Database connection failed in NotificationDao.");
        }
    }

    public function createNotification($data) {

        $query = "INSERT INTO notifications
                  (user_id, message)
                  VALUES
                  (:user_id, :message)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':message' => $data['message']
        ]);
    }

    public function getUserNotifications($userId) {

        $query = "SELECT *
                  FROM notifications
                  WHERE user_id = :user_id
                  ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}