<?php

namespace App\Models;

class User
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * Constructor del modelo `User`
     * 
     * @param \PDO $conn Conexión a la base de datos
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * Obtiene todos los usuarios de la tabla User
     * 
     * @return array
     */
    public function getAllUser()
    {
        $stmt = $this->sql->prepare("SELECT * FROM User");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Inserta un nuevo usuario en la base de datos
     */
    public function insertUser($name, $surname, $email, $password, $role, $avatar)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $query = "INSERT INTO User (name, surname, email, password, role, avatar) 
                      VALUES (:name, :surname, :email, :password, :role, :avatar)";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'avatar' => $avatar
            ]);
        } catch (\PDOException $e) {
            throw new \Exception("Error en la inserción: " . $e->getMessage());
        }
    }

    /**
     * Actualiza un usuario existente en la base de datos
     */
    public function updateUser($id, $name, $surname, $email, $password, $role, $avatar)
    {
        $params = [
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'role' => $role,
            'avatar' => $avatar
        ];

        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $params['password'] = $password;
            $query = "UPDATE User 
                      SET name = :name, surname = :surname, email = :email, password = :password, role = :role, avatar = :avatar 
                      WHERE id = :id";
        } else {
            $query = "UPDATE User 
                      SET name = :name, surname = :surname, email = :email, role = :role, avatar = :avatar 
                      WHERE id = :id";
        }

        $stmt = $this->sql->prepare($query);
        $stmt->execute($params);
    }

    /**
     * Elimina un usuario por su ID
     */
    public function deleteUser($id)
    {
        try {
            $query = "DELETE FROM User WHERE id = :id";
            $stmt = $this->sql->prepare($query);
            $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            throw new \Exception("Error en la eliminación: " . $e->getMessage());
        }
    }

    /**
     * Actualiza el token de restablecimiento de contraseña
     */
    public function updateResetToken($email, $tokenHash, $expiresAt)
    {
        try {
            $query = "UPDATE User SET reset_token_hash = :tokenHash, reset_token_expires_at = :expiresAt WHERE email = :email";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'tokenHash' => $tokenHash,
                'expiresAt' => $expiresAt,
                'email' => $email,
            ]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            error_log("Error al actualizar el token de recuperación: " . $e->getMessage());
            var_dump($e->getMessage()); // Agregar para ver el error directamente
            return false;
        }
    }


    /**
     * Obtiene un usuario basado en su token de recuperación
     */
    public function getUserByToken($tokenHash)
    {
        try {
            $query = "SELECT * FROM User WHERE reset_token_hash = :token AND reset_token_expires_at > NOW()";
            $stmt = $this->sql->prepare($query);
            $stmt->execute(['token' => $tokenHash]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                error_log("No se encontró usuario con el token: " . $tokenHash);
                return null;
            }

            return $result;
        } catch (\PDOException $e) {
            error_log("Error en getUserByToken: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza la contraseña de un usuario por su email
     */
    public function updatePassword($token, $password)
    {
        try {
            // Hashear la nueva contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $stmt = $this->sql->prepare(
                "UPDATE User SET password = :password, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = :reset_token_hash"
            );


            // Ejecutar la consulta
            return $stmt->execute(['password' => $hashedPassword, 'reset_token_hash' => $token]);
        } catch (\PDOException $e) {
            error_log("Error al actualizar la contraseña: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene un usuario por su ID
     */
    public function getUserById($id)
    {
        $stmt = $this->sql->prepare("SELECT * FROM User WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
