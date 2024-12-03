<?php

namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class CtrlEditMachine
{
    public function editMachine(Request $request, Response $response, Container $container)
    {
        $machineModel = $container->get("Machine");

        // Capturar datos enviados por el formulario (POST)
        $id = $request->get(INPUT_POST, 'id');
        $name = $request->get(INPUT_POST, 'name');
        $model = $request->get(INPUT_POST, 'model');
        $manufacturer = $request->get(INPUT_POST, 'manufacturer');
        $location = $request->get(INPUT_POST, 'location');
        $installation_date = $request->get(INPUT_POST, 'installation_date');
        $serial_number = $request->get(INPUT_POST, 'serial_number');
        $photo = $request->get(INPUT_POST, 'photo');
        $coordinates = $request->get(INPUT_POST, 'coordinates');

        // Procesar la subida del archivo (avatar)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $photo = $_FILES['photo']['name'];
            $uploadPath = __DIR__ . "/../../public/Images/" . $photo;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                die("Error al subir el archivo.");
            }
        } else {
            // Si no se sube una nueva imagen, mantener la actual
            $photo = $photo;
        }

        // Llamar al modelo para actualizar el usuario
        $machineModel->updateMachine($id, $name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates);

        // Redirigir a machineinv
        $response->redirect("Location:/machineinv");
        return $response;
    }
}
