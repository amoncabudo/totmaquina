<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use \Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use \Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class MachineController
{
    // Function to assign a user to a machine
    public function assignUser(Request $request, Response $response, Container $container)
    {
        // Getting the machine ID and user ID from the POST request
        $machineId = $request->get(INPUT_POST, 'machine_id');
        $userId = $request->get(INPUT_POST, 'user_id');

        // Retrieving the machine model from the container
        $machineModel = $container->get('Machine');

        // Assigning the user to the machine by calling the assignUserToMachine method
        $machineModel->assignUserToMachine($machineId, $userId);

        // Redirecting to the machine inventory page after assignment
        $response->redirect("Location: /machineinv");

        // Returning the response object
        return $response;
    }
}
