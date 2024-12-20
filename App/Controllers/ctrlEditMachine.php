<?php

namespace App\Controllers;

use Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class CtrlEditMachine
{
    // Function to edit the machine details based on the submitted data
    public function editMachine(Request $request, Response $response, Container $container)
    {
        // Retrieving the Machine model from the container
        $machineModel = $container->get("Machine");

        // Capturing data sent by the form (POST request)
        $id = $request->get(INPUT_POST, 'id');
        $name = $request->get(INPUT_POST, 'name');
        $model = $request->get(INPUT_POST, 'model');
        $manufacturer = $request->get(INPUT_POST, 'manufacturer');
        $location = $request->get(INPUT_POST, 'location');
        $installation_date = $request->get(INPUT_POST, 'installation_date');
        $serial_number = $request->get(INPUT_POST, 'serial_number');
        $photo = $request->get(INPUT_POST, 'photo');
        $coordinates = $request->get(INPUT_POST, 'coordinates');

        // Processing the file upload for the photo (avatar)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $photo = $_FILES['photo']['name'];
            $uploadPath = __DIR__ . "/../../public/Images/" . $photo;

            // Moving the uploaded file to the target directory
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                die("Error uploading the file."); // Error message if the upload fails
            }
        } else {
            // If no new image is uploaded, retain the existing photo
            $photo = $photo;
        }

        // Calling the model method to update the machine with the new data
        $machineModel->updateMachine($id, $name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates);

        // Redirecting to the machine inventory page after updating the machine details
        $response->redirect("Location:/machineinv");

        // Returning the response object
        return $response;
    }
}
