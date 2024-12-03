<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class getMachinebyid
{
function ctrlMachineDetail($request, $response, $container)
{
    
    $machine_id = $request->getParam('id');
    error_log("Machine ID: " . $machine_id);

    if ($machine_id) {
        $machineModel = $container->get('Machine');
        $machine = $machineModel->getMachineById($machine_id);
        error_log("Machine Data: " . print_r($machine, true));

        if ($machine) {
            $userModel = $container->get('User');
            $users = $userModel->getAllUser();

            $response->set('machine', $machine);
            $response->set('users', $users);
            $response->setTemplate('machine_detail.php');
            return $response;
        }
    }

    // Si no se encuentra la máquina, mostrar error
    $response->set('error', "Máquina no encontrada.");
    $response->setTemplate('error.php');
    return $response;
}
}
