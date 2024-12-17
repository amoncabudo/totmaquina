<?php

function incidents($request, $response, $container) {
    try {
        error_log("=== DEBUG: Cargando página de incidencias ===");
        
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Obtener los datos necesarios para la vista
        $technicians = $incident->getAllTechnicians();
        $machines = $incident->getAllMachines();
        $allIncidents = $incident->getAllIncidents();
        
        error_log("Técnicos cargados: " . count($technicians));
        error_log("Máquinas cargadas: " . count($machines));
        error_log("Incidencias cargadas: " . count($allIncidents));
        
        // Pasar los datos a la vista
        $response->set("technicians", $technicians);
        $response->set("machines", $machines);
        $response->set("incidents", $allIncidents);
        
        $response->setTemplate('incidents.php');
        return $response;
    } catch (\Exception $e) {
        error_log("Error al cargar la página de incidencias: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        $_SESSION['error_message'] = "Error al cargar la página: " . $e->getMessage();
        header('Location: /');
        exit;
    }
}

function createIncident($request, $response, $container) {
    try {
        error_log("=== DEBUG: Iniciando creación de incidencia ===");
        
        // Validar que el usuario esté autenticado
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            throw new \Exception("No autorizado");
        }

        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Obtener y validar los datos del formulario
        $description = $request->get(INPUT_POST, "description");
        $priority = $request->get(INPUT_POST, "priority");
        $machineId = $request->get(INPUT_POST, "machine_id");
        $technicianId = $request->get(INPUT_POST, "responsible_technician_id");

        error_log("Datos del formulario:");
        error_log("- Descripción: " . $description);
        error_log("- Prioridad: " . $priority);
        error_log("- ID Máquina: " . $machineId);
        error_log("- ID Técnico: " . $technicianId);

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
            'responsible_technician_id' => $technicianId
        ];
        
        error_log("Datos preparados para el modelo: " . print_r($data, true));
        
        // Crear la incidencia
        $incidentId = $incident->create($data);
        
        if ($incidentId) {
            error_log("Incidencia creada exitosamente con ID: " . $incidentId);
            
            // Verificar que la incidencia se puede recuperar
            $newIncident = $incident->getIncidentById($incidentId);
            error_log("Datos de la nueva incidencia: " . print_r($newIncident, true));
            
            $_SESSION['success_message'] = "Incidencia creada correctamente";
            header('Location: /incidents');
            exit;
        } else {
            throw new \Exception("Error al crear la incidencia");
        }
        
    } catch (\Exception $e) {
        error_log("=== ERROR en createIncident ===");
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: /incidents');
        exit;
    }
}