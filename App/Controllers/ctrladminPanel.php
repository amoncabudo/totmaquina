<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrladminPanel
{
    function adminPanel(Request $request, Response $response, Container $container)
    {
        $response->setTemplate('adminPanel.php');
        return $response;
    }
}
