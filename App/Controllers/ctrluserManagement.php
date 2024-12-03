<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class getUser
{
    function ctrlUserManagement($request, $response, $container)
    {
        $userdb = $container->get("User");
        $userdb = $userdb->getAllUser();
      
        $response->set("users", $userdb);
        $response->setTemplate('userManagement.php');
        return $response;
    }
}
