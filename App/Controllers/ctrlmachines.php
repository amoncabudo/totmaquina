<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachines
{
    public function ctrlmachines(Request $request, Response $response, Container $container)
    {
        // Obtener el modelo de máquinas desde el contenedor
        $machineModel = $container->get("Machine");

        // Obtener todas las máquinas
        $machines = $machineModel->getAllMachine();

        // Verificar si se obtuvieron máquinas
        if (empty($machines)) {
            $error = "No se pudieron obtener las máquinas desde la base de datos.";
            $response->set("error", $error); // Establecer mensaje de error
            $machines = [];  // Asegurarse de que $machines sea un array vacío si no hay datos
        }

        // Pasar las máquinas a la vista
        $response->set("machines", $machines);

        // Definir el template a utilizar
        $response->setTemplate('machines.php');

        return $response;
    }
}
