<?php

namespace App\Controllers;
use Emeset\Controller;
use Emeset\Request;
use Emeset\Response;

class ctrlMapMachine 
{
    public function mapmachines($request, $response, $container){
        $machineModel = $container->get("Machine");
        $machines = $machineModel->getAllMachine();

        
        $response->set("machines", $machines);
        $response->setTemplate('/map-machines.php');
        return $response;
    }
}