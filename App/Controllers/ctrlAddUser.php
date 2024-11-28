<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class UserController
{

    function createUser($request, $response, $container)
    {
  
        $name = $request->get(INPUT_POST, 'name');
        $surname = $request->get(INPUT_POST, 'surname');
        $email = $request->get(INPUT_POST, 'email');
        $password = $request->get(INPUT_POST, 'password');
        $role = $request->get(INPUT_POST, 'role');

        $avatar = null;

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar']['name'];
            move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . "/../../public/Images/" . $avatar);
        }
        $userdb = $container->get("User");
        $result = $userdb->insertUser($name, $surname, $email, $password, $role, $avatar);


        $response->redirect("Location: /userManagement");
        return $response;
    }

}
