<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachineinv
{
    function ctrlmachineinv($request, $response, $container)
    {
        $machineModel = $container->get("Machine");
        $machines = $machineModel->getAllMachine();

        $response->set("machines", $machines);
        $response->setTemplate('machineinv.php');
        return $response;
    }
}
