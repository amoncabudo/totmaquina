<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class UserConfig {
    public function index(Request $request, Response $response, Container $container) :Response
    {
        // Verificar si el usuario está logueado usando la sesión del framework
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            header("Location: /login");
            exit();
        }

        // Obtener datos del usuario de la sesión
        $user = $_SESSION["user"] ?? null;
        
        if (!$user) {
            header("Location: /login");
            exit();
        }

        $response->set("user", $user);
        $response->SetTemplate("userConfig.php");
        
        return $response;
    }

    public function updateProfile(Request $request, Response $response, Container $container) :Response
    {
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            header("Location: /login");
            exit();
        }

        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            header("Location: /login");
            exit();
        }

        $username = $request->get(INPUT_POST, "username");
        $currentPassword = $request->get(INPUT_POST, "currentPassword");
        $newPassword = $request->get(INPUT_POST, "newPassword");
        
        // Verificar contraseña actual
        $sql = "SELECT password FROM User WHERE id = :id";
        $stmt = $container->get("db")->prepare($sql);
        $stmt->execute(['id' => $user['id']]);
        $dbUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $dbUser['password'])) {
            $response->set("error", "La contraseña actual es incorrecta");
            $response->set("user", $user);
            $response->SetTemplate("userConfig.php");
            return $response;
        }

        // Actualizar datos
        if ($newPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE User SET name = :name, password = :password WHERE id = :id";
            $params = [
                'name' => $username,
                'password' => $hashedPassword,
                'id' => $user['id']
            ];
        } else {
            $sql = "UPDATE User SET name = :name WHERE id = :id";
            $params = [
                'name' => $username,
                'id' => $user['id']
            ];
        }

        $stmt = $container->get("db")->prepare($sql);
        $stmt->execute($params);

        // Actualizar la sesión con el nuevo nombre
        $user['name'] = $username;
        $_SESSION["user"] = $user;
        
        $response->set("success", "Perfil actualizado correctamente");
        $response->set("user", $user);
        $response->SetTemplate("userConfig.php");
        
        return $response;
    }
} 