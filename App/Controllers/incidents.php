<?php

function incidents($request, $response, $container) {
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    $incident = new \App\Models\Incident($db->getConnection());
    
    // Obtener los datos necesarios para la vista
    $technicians = $incident->getAllTechnicians();
    $machines = $incident->getAllMachines();
    $allIncidents = $incident->getAllIncidents();
    
    // Pasar los datos a la vista
    $response->set("technicians", $technicians);
    $response->set("machines", $machines);
    $response->set("incidents", $allIncidents);
    
    $response->setTemplate('incidents.php');
    return $response;
}

function createIncident($request, $response, $container) {
    try {
        $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Obtener los datos del formulario
        $data = [
            'description' => $request->get(INPUT_POST, "description"),
            'priority' => $request->get(INPUT_POST, "priority"),
            'machine_id' => $request->get(INPUT_POST, "machine_id"),
            'technicians' => $request->get(INPUT_POST, "technicians", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? []
        ];
        
        // Crear la incidencia
        if ($incident->create($data)) {
            // Redirigir a la página de incidencias
            header('Location: /incidents');
            exit;
        } else {
            throw new \Exception("Error al crear la incidencia");
        }
        
    } catch (\Exception $e) {
        error_log("Error en createIncident: " . $e->getMessage());
        $response->setStatus(500);
        $response->setBody(json_encode([
            'error' => true,
            'message' => $e->getMessage()
        ]));
    }
    
    return $response;
}