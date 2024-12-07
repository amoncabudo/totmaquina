<?php

/**
 * Exemple per a M07 i M08.
 * Model que gestiona les tasques amb PDO.
 * 
 * @autor: Dani Prados dprados@cendrassos.net
 */

namespace App\Models;

/**
 * Tasques: model que gestiona les tasques.
 * Per guardar, recuperar i gestionar les tasques.
 * 
 * @autor: Dani Prados dprados@cendrassos.net
 */
class User
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el model tasques
     * Model adaptat per PDO
     * 
     * @param \App\Models\Db $conn connexió a la base de dades
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * getAllUser: obtenir tots els usuaris
     * 
     * @return array
     */
    public function getAllUser()
    {
        $stmt = $this->sql->prepare("SELECT * FROM User");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function insertUser($name, $surname, $email, $password, $role, $avatar)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $query = "INSERT INTO User (name, surname, email, password, role, avatar) VALUES (:name, :surname, :email, :password, :role, :avatar)";
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
            echo "Error en la inserción: " . $e->getMessage();
            exit;
        }
    }

    public function updateUser($id, $name, $surname, $email, $password, $role, $avatar)
    {
        // Si no se proporciona una nueva contraseña, no se actualiza
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $query = "UPDATE User 
                      SET name = :name, surname = :surname, email = :email, password = :password, role = :role, avatar = :avatar 
                      WHERE id = :id";

            $params = [
                'id' => $id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'password' => $password,
                'role' => $role,
                'avatar' => $avatar
            ];
        } else {
            $query = "UPDATE User 
                      SET name = :name, surname = :surname, email = :email, role = :role, avatar = :avatar 
                      WHERE id = :id";

            $params = [
                'id' => $id,
                'name' => $name,
                'surname' => $surname,
                'email' => $email,
                'role' => $role,
                'avatar' => $avatar
            ];
        }

        $stmt = $this->sql->prepare($query);
        $stmt->execute($params);
    }



    public function deleteUser($id)
    {
        try {
            $query = "DELETE FROM User WHERE id = :id";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'id' => $id
            ]);
        } catch (\PDOException $e) {
            echo "Error en la eliminación: " . $e->getMessage();
            exit;
        }
    }

    public function updateResetToken($email, $tokenHash, $expiresAt)
    {
        $query = "UPDATE User SET reset_token_hash = :tokenHash, reset_token_expires_at = :expiresAt WHERE email = :email";
        $stmt = $this->sql->prepare($query);
        return $stmt->execute([
            'tokenHash' => $tokenHash, 
            'expiresAt' => $expiresAt, 
            'email' => $email
        ]);
    }

    public function getUserByToken($tokenHash)
    {
        $query = "SELECT * FROM User WHERE reset_token_hash = :tokenHash AND reset_token_expires_at > NOW()";
        $stmt = $this->sql->prepare($query);
        $stmt->execute(['tokenHash' => $tokenHash]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "UPDATE User SET password = :password WHERE email = :email";
        $stmt = $this->sql->prepare($query);
        return $stmt->execute(['password' => $password, 'email' => $email]);
    }


    public function getUserById($id)
    {
        $stmt = $this->sql->prepare("SELECT * FROM User WHERE id = :id");
        $stmt->execute([
            'id' => $id
        ]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
