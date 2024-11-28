<?php

function ctrlAddMachine($request, $response, $container){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $name = $request->get(INPUT_POST, 'name');
        $model = $request->get(INPUT_POST, 'model');
        $manufacturer = $request->get(INPUT_POST, 'manufacturer');
        $location = $request->get(INPUT_POST, 'location');
        $installation_date = $request->get(INPUT_POST, 'installation_date');
        $serial_number = $request->get(INPUT_POST, 'serial_number');
        $photo = $request->get("FILES", 'photo');

        $unique_id = uniqid();
        $dir_file = "uploads/img/" . $unique_id . "_" . $photo['name'];
        move_uploaded_file($photo["tmp_name"], $dir_file);

        $machineModel = $container->Machine();
        $machineModel->addMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number);
    }

    $response->redirect("Location: addmachine.php"); 
    return $response;
}