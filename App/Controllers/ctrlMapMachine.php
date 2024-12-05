<?php

namespace App\Controllers;
use Emeset\Controller;
use Emeset\Request;
use Emeset\Response;

class ctrlMapMachine 
{
    public function mapmachines($request, $response, $container){
        $response->setTemplate('/map-machines.php');
        return $response;
    }
}