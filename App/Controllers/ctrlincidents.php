<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;
use App\Models\Machine;
use App\Models\User;

class incidents {
    public function index($request, $response, $container) {
        // Obtener todas las máquinas
        $machineModel = new Machine($container->get('db'));
        $machines = $machineModel->getAllMachine();

        // Obtener todos los usuarios y filtrar técnicos
        $userModel = new User($container->get('db'));
        $allUsers = $userModel->getAllUser();
        $technicians = array_filter($allUsers, function ($user) {
            return isset($user['role']) && $user['role'] === 'technician';
        });

        // Pasar datos a la vista
        $response->set("machines", $machines);
        $response->set("technicians", $technicians);

        // Establecer la plantilla
        $response->setTemplate('incidents.php');

        return $response;
    }
}
   
    
?>
