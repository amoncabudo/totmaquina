<?php
namespace App\Models;

class Auth {
    private $db;

    // Constructor to initialize the database connection
    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    // Function to handle user login
    public function login($email, $password) {
        $query = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Verify the password and return user data if valid
        if ($user && password_verify($password, $user['password'])) {
            // Remove the password before returning user data
            unset($user['password']);
            return $user;
        }
        return false;
    }

    // Function to get user data by ID
    public function getUserById($id) {
        $query = "SELECT id, name, surname, email, role, avatar FROM User WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}