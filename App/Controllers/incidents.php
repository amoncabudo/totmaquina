<?php
function incidents($request, $response, $container) {
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    
    $incident = new \App\Models\Incident($db->getConnection());
    
    // Obtener los datos necesarios para la vista
    $technicians = $incident->getAllTechnicians();
    $machines = $incident->getAllMachines();
    
    // Obtener todas las incidencias para mostrarlas
    $allIncidents = $incident->getAllIncidents();
    
    // Pasar los datos a la vista
    $response->set("technicians", $technicians);
    $response->set("machines", $machines);
    $response->set("incidents", $allIncidents);
    $response->set("incident", $incident);
    
    $response->setTemplate('incidents.php');
    return $response;
}

function createIncident($request, $response, $container) {
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    
    $incident = new \App\Models\Incident($db->getConnection());
    
    // Obtener los datos del formulario usando INPUT_POST
    $description = $request->get(INPUT_POST, "description");
    $machine_id = $request->get(INPUT_POST, "machine_id");
    $priority = $request->get(INPUT_POST, "priority");
    $technicians = $request->get(INPUT_POST, "technicians");
    
    $data = [
        'description' => $description,
        'machine_id' => $machine_id,
        'priority' => $priority,
        'technicians' => $technicians
    ];
    
    $result = $incident->create($data);
    

    exit;
}