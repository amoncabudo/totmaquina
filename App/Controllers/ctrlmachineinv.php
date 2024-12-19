<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachineinv
{
    // Function to handle the machine inventory request
    function ctrlmachineinv($request, $response, $container)
    {
        // Get the Machine model from the container
        $machineModel = $container->get("Machine");

        // Retrieve all the machines from the database
        $machines = $machineModel->getAllMachine();

        // Set the list of machines to the response object
        $response->set("machines", $machines);

        // Set the template to 'machineinv.php' for displaying the machine inventory
        $response->setTemplate('machineinv.php');
        
        // Return the response
        return $response;
    }
}
?>
