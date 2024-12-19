<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use \Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use \Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class ctrlDeleteMachine
{
    // Function to delete a machine based on the provided machine ID
    public function deleteMachine(Request $request, Response $response, Container $container)
    {
        // Retrieving the machine ID from the request parameters
        $machineId = $request->getParam('id');

        // Checking if the machine ID exists
        if ($machineId) {
            // Retrieving the machine model from the container
            $machineModel = $container->get('Machine');

            // Deleting the machine by calling the deleteMachine method
            $machineModel->deleteMachine($machineId);

            // Redirecting to the machine inventory page after successful deletion
            header("Location: /machineinv");
            exit; // Exiting the script to ensure the redirect happens immediately
        }

        // If machine ID is not found, set an error message and render the error template
        $response->set('error', "Máquina no encontrada.");
        $response->setTemplate('error.php');

        // Returning the response object with the error template
        return $response;
    }
}
