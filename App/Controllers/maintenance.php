<?php

function maintenance($request, $response, $container) {
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    
    $maintenance = new \App\Models\Maintenance($db->getConnection());
    
    // Obtener los datos necesarios para la vista
    $technicians = $maintenance->getAllTechnicians();
    $machines = $maintenance->getAllMachines();
    $allMaintenances = $maintenance->getAllMaintenances();
    
    // Pasar los datos a la vista
    $response->set("technicians", $technicians);
    $response->set("machines", $machines);
    $response->set("maintenances", $allMaintenances);
    
    $response->setTemplate('maintenance.php');
    return $response;
}

function createMaintenance($request, $response, $container) {
    // Desactivar la salida del buffer
    @ob_end_clean();
    
    // Iniciar un nuevo buffer limpio
    ob_start();
    
    try {
        $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
        $maintenance = new \App\Models\Maintenance($db->getConnection());
        
        // Obtener los datos del formulario usando FILTER_SANITIZE_STRING
        $scheduled_date = $request->get(INPUT_POST, "scheduled_date", FILTER_SANITIZE_STRING);
        $frequency = $request->get(INPUT_POST, "frequency", FILTER_SANITIZE_STRING);
        $type = $request->get(INPUT_POST, "type", FILTER_SANITIZE_STRING);
        $machine_id = $request->get(INPUT_POST, "machine_id", FILTER_SANITIZE_NUMBER_INT);
        $technicians = $request->get(INPUT_POST, "technicians", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $description = $request->get(INPUT_POST, "description", FILTER_SANITIZE_STRING) ?? '';

        // Debug: Imprimir los datos recibidos
        error_log("Datos recibidos en createMaintenance:");
        error_log("scheduled_date: " . $scheduled_date);
        error_log("frequency: " . $frequency);
        error_log("type: " . $type);
        error_log("machine_id: " . $machine_id);
        error_log("technicians: " . print_r($technicians, true));
        error_log("description: " . $description);
        
        // Validar datos requeridos
        if (empty($scheduled_date) || empty($frequency) || empty($type) || empty($machine_id)) {
            throw new \Exception("Faltan campos requeridos");
        }

        // Preparar los datos para el modelo
        $data = [
            'scheduled_date' => $scheduled_date,
            'frequency' => $frequency,
            'type' => $type,
            'machine_id' => $machine_id,
            'technicians' => $technicians,
            'description' => $description
        ];

        // Crear el mantenimiento
        $result = $maintenance->create($data);
        
        // Limpiar cualquier salida que pudiera haberse generado
        @ob_end_clean();
        
        // Configurar headers
        header('Content-Type: application/json');
        
        // Preparar la respuesta
        $jsonResponse = [
            "success" => true,
            "message" => "Mantenimiento registrado correctamente"
        ];
        
        // Registrar la respuesta que vamos a enviar
        error_log("Enviando respuesta JSON: " . json_encode($jsonResponse));
        
        // Enviar la respuesta
        echo json_encode($jsonResponse);
        exit;
        
    } catch (\Exception $e) {
        // Limpiar cualquier salida que pudiera haberse generado
        @ob_end_clean();
        
        // Registrar el error
        error_log("Error en createMaintenance: " . $e->getMessage());
        
        // Configurar headers
        header('Content-Type: application/json');
        http_response_code(500);
        
        // Enviar respuesta de error
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ]);
        exit;
    }
}