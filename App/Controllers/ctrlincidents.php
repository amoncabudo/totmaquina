<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;
use App\Models\Machine;
use App\Models\User;

class incidents {
    // Method to handle displaying incidents page
    public function index($request, $response, $container) {
        // Get all machines from the database
        $machineModel = new Machine($container->get('db'));
        $machines = $machineModel->getAllMachine();

        // Get all users and filter out technicians
        $userModel = new User($container->get('db'));
        $allUsers = $userModel->getAllUser();
        // Filter users by role "technician"
        $technicians = array_filter($allUsers, function ($user) {
            return isset($user['role']) && $user['role'] === 'technician';
        });

        // Pass the data to the view
        $response->set("machines", $machines); // Pass the machines list
        $response->set("technicians", $technicians); // Pass the list of technicians

        // Set the template for rendering
        $response->setTemplate('incidents.php');

        // Return the response
        return $response;
    }
}

?>
