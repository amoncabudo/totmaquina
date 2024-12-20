<?php

namespace App\Controllers;
use Emeset\Controller;
use Emeset\Request;
use Emeset\Response;

class ctrlMapMachine 
{
    // Method to handle the mapping of machines
    public function mapmachines($request, $response, $container){
        // Retrieve the 'Machine' model from the container
        $machineModel = $container->get("Machine");
        
        // Fetch all machines using the model's method
        $machines = $machineModel->getAllMachine();

        // Set the machines data in the response
        $response->set("machines", $machines);
        
        // Set the template to be used for rendering
        $response->setTemplate('/map-machines.php');
        
        // Return the response
        return $response;
    }
}
?>
