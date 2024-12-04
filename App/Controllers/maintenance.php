<?php
function maintenance($request, $response, $container) {
    // Conectar a la base de dades
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    
    // Crear una instància del model Machine
    $machineModel = new \App\Models\Machine($db->getConnection());
    
    // Obtenir totes les màquines
    $machines = $machineModel->getAllMachine();
    
    // Passar les màquines a la vista
    $response->set("machines", $machines);
    
    // Renderitzar la plantilla de manteniment
    $response->setTemplate('maintenance.php');
    return $response;
}
