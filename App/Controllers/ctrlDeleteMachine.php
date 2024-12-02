<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlDeleteMachine
{
    public function deleteMachine(Request $request, Response $response, Container $container)
    {
        $machineId = $request->getParam('id');

        if ($machineId) {
            $machineModel = $container->get('Machine');
            $machineModel->deleteMachine($machineId);

            header("Location: /machineinv");
            exit;
        }

        $response->set('error', "Máquina no encontrada.");
        $response->setTemplate('error.php');
        return $response;
    }
}