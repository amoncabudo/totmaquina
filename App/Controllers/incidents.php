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
        // Validar que el usuario esté autenticado
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            throw new \Exception("No autorizado");
        }

        $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Obtener y validar los datos del formulario
        $description = $request->get(INPUT_POST, "description");
        $priority = $request->get(INPUT_POST, "priority");
        $machineId = $request->get(INPUT_POST, "machine_id");
        $technicianId = $request->get(INPUT_POST, "technicians");

        // Debug: Imprimir los datos recibidos
        error_log("Datos recibidos en createIncident:");
        error_log("Descripción: " . $description);
        error_log("Prioridad: " . $priority);
        error_log("ID Máquina: " . $machineId);
        error_log("ID Técnico: " . $technicianId);

        // Validaciones básicas
        if (empty($description)) {
            throw new \Exception("La descripción es obligatoria");
        }
        if (empty($machineId)) {
            throw new \Exception("Debe seleccionar una máquina");
        }
        if (empty($technicianId)) {
            throw new \Exception("Debe seleccionar un técnico");
        }

        // Preparar los datos para el modelo
        $data = [
            'description' => $description,
            'priority' => $priority,
            'machine_id' => $machineId,
            'technicians' => $technicianId
        ];
        
        // Debug: Imprimir los datos que se enviarán al modelo
        error_log("Datos a enviar al modelo: " . print_r($data, true));
        
        // Crear la incidencia
        $incidentId = $incident->create($data);
        
        if ($incidentId) {
            $_SESSION['success_message'] = "Incidencia creada correctamente";
            header('Location: /incidents');
            exit;
        } else {
            throw new \Exception("Error al crear la incidencia");
        }
        
    } catch (\Exception $e) {
        error_log("Error en createIncident: " . $e->getMessage());
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: /incidents');
        exit;
    }
}