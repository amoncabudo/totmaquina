<?php
namespace App\Models;

class Auth {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function login($email, $password) {
        $query = "SELECT * FROM User WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Eliminar la contraseña antes de devolver los datos del usuario
            unset($user['password']);
            return $user;
        }
        return false;
    }

    public function getUserById($id) {
        $query = "SELECT id, name, surname, email, role, avatar FROM User WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
} 