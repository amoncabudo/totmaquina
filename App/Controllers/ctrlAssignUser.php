<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class MachineController
{
    public function assignUser(Request $request, Response $response, Container $container)
    {
        $machineId = $request->get(INPUT_POST, 'machine_id');
        $userId = $request->get(INPUT_POST, 'user_id');

        $machineModel = $container->get('Machine');
        $machineModel->assignUserToMachine($machineId, $userId);

        $response->redirect("Location: /machineinv");
        return $response;
    }
} 