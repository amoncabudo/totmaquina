<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class EditMachineController
{
    public function editMachine($request, $response, $container)
    {
        $machine_id = $request->getParam('id');
        $machineModel = $container->get('Machine');

        if ($request->isPost()) {
            // Get data from the form
            $data = $request->getParsedBody();
            $machineModel->updateMachine($machine_id, $data);

            // Redirect to the machine detail page
            return $response->withRedirect("/machinedetail/$machine_id");
        }

        $machine = $machineModel->getMachineById($machine_id);
        $response->set('machine', $machine);
        $response->setTemplate('edit_machine.php');
        return $response;
    }
}
