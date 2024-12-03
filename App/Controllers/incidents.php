<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;
use App\Models\Machine;

class Incidents {
    public function index($request, $response, $container) {
        // Obtener todas las máquinas desde el modelo
        $machineModel = new Machine($container->get('db')); // Conexión a la base de datos
        $machines = $machineModel->getAllMachine(); // Método para obtener todas las máquinas
    
        // Pasar las máquinas a la vista
        $response->set("machines", $machines);
    
        // Establecer la plantilla que se usará
        $response->setTemplate('incidents.php');
        
        return $response;
    }
    
}
?>
