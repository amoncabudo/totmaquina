<?php

namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class editUser
{
    public function editUser(Request $request, Response $response, Container $container)
    {
        $userdb = $container->get("User");

        // Capturar datos enviados por el formulario (POST)
        $id = $request->get(INPUT_POST, 'id');
        $name = $request->get(INPUT_POST, 'name');
        $surname = $request->get(INPUT_POST, 'surname');
        $email = $request->get(INPUT_POST, 'email');
        $password = $request->get(INPUT_POST, 'password');
        $role = $request->get(INPUT_POST, 'role');
        $currentAvatar = $request->get(INPUT_POST, 'current_avatar');

        // Procesar la subida del archivo (avatar)
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            $avatar = $_FILES['avatar']['name'];
            $uploadPath = __DIR__ . "/../../public/Images/" . $avatar;
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                die("Error al subir el archivo.");
            }
        } else {
            // Si no se sube una nueva imagen, mantener la actual
            $avatar = $currentAvatar;
        }

        // Llamar al modelo para actualizar el usuario
        $userdb->updateUser($id, $name, $surname, $email, $password, $role, $avatar);

        // Redirigir a la página de gestión de usuarios
        $response->redirect("Location:/userManagement");
        return $response;
    }
}
