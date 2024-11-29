<?php
namespace App\Models;

class Notification {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($page = 1, $per_page = 10) {
        $offset = ($page - 1) * $per_page;
        $query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return $this->db->query($query, [$per_page, $offset]);
    }

    public function getTotal() {
        $query = "SELECT COUNT(*) as total FROM notifications";
        $result = $this->db->query($query);
        return $result[0]['total'];
    }

    public function getUnreadCount() {
        $query = "SELECT COUNT(*) as count FROM notifications WHERE status = 'unread'";
        $result = $this->db->query($query);
        return $result[0]['count'];
    }

    public function markAsRead($id) {
        $query = "UPDATE notifications SET status = 'read' WHERE id = ?";
        return $this->db->query($query, [$id]);
    }

    public function delete($id) {
        $query = "DELETE FROM notifications WHERE id = ?";
        return $this->db->query($query, [$id]);
    }

    public function create($data) {
        $query = "INSERT INTO notifications (type, title, message, status) VALUES (?, ?, ?, 'unread')";
        return $this->db->query($query, [
            $data['type'],
            $data['title'],
            $data['message']
        ]);
    }
} 