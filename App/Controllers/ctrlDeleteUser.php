<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class deleteUser {
    function deleteUser(Request $request, Response $response, Container $container) {
        $id = $request->get(INPUT_POST, 'id');
        
        $userdb = $container->get("User");
        $userdb->deleteUser($id);

        $response->redirect("Location: /userManagement");
        return $response;
    }
}
