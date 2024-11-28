<?php
function ctrlMachineInv($request, $response, $container){
    // Get the Machine model from the container
    $machineModel = $container->get('Machine');
    
    // Fetch all machine data from the database
    $machines = $machineModel->getAllMachine();
    
    $response->setTemplate('machineinv.php');
    $response->set('machines', $machines);
    return $response;
}