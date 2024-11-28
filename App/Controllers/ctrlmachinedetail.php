<?php
function ctrlmachinedetail($request, $response, $container)
{
    
    $machine_id = $request->getParam('id');

    if ($machine_id) {
        $machineModel = $container->get('Machine');
        $machine = $machineModel->getMachineById($machine_id);

        if ($machine) {
            $response->set('machine', $machine);
            $response->setTemplate('machine_detail.php');
            return $response;
        }
    }

    // Si no se encuentra la máquina, mostrar error
    $response->set('error', "Máquina no encontrada.");
    $response->setTemplate('error.php');
    return $response;
}
