<?php
function ctrlMachineDetail($request, $response, $container){
    $machineId = $request->get(INPUT_GET, 'id');
    
    // Get the Machine model from the container
    $machineModel = $container->get('Machine');
    
    // Fetch detailed machine data based on $machineId
    $machine = $machineModel->getMachineById($machineId);
    
    if (!$machine) {
        // Handle the case where the machine is not found
        $response->setTemplate('error.php');
        $response->setData(['error' => 'Machine not found']);
        return $response;
    }
    
    $response->setTemplate('machine_detail.php');
    $response->setData(['machine' => $machine]);
    return $response;
}