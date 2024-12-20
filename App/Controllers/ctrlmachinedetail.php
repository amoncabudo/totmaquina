<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachinedetail
{
    // Function to handle the machine detail request
    function ctrlMachineDetail($request, $response, $container)
    {
        // Get the 'id' parameter from the URL (the machine ID)
        $machine_id = $request->getParam('id');
        error_log("Machine ID: " . $machine_id);

        if ($machine_id) {
            // Get the Machine model from the container
            $machineModel = $container->get('Machine');
            
            // Retrieve the machine details by its ID
            $machine = $machineModel->getMachineById($machine_id);
            error_log("Machine Data: " . print_r($machine, true));

            if ($machine) {
                // Get the User model from the container
                $userModel = $container->get('User');
                
                // Retrieve all users
                $users = $userModel->getAllUser();
                
                // Filter the users to get only the technicians
                $technicians = array_filter($users, function ($user) {
                    return isset($user['role']) && $user['role'] === 'technician';
                });

                // Set the data for the response: machine details, technicians, and all users
                $response->set('machine', $machine);
                $response->set('technicians', $technicians);
                $response->set('users', $users);
                
                // Set the template to 'machine_detail.php' for displaying the machine's details
                $response->setTemplate('machine_detail.php');
                
                return $response;
            }
        }

        // If the machine is not found, show an error
        $response->set('error', "Machine not found.");
        $response->setTemplate('error.php');
        return $response;
    }
}
?>
