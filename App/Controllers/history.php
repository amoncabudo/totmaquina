<?php
function history($request, $response, $container) {
    try {
        // Usar el modelo Machine desde el container
        $model = $container->get("Machine");
        
        // Obtener la lista de máquinas
        $machines = $model->getAllMachine();
        
        // Pasar los datos a la vista
        $response->set("machines", $machines);
        $response->set("title", "Historial de Incidencias");
        
        return $response->setTemplate('history.php');
    } catch (\Exception $e) {
        error_log("Error en history: " . $e->getMessage());
        $response->set("error", "Error al cargar las máquinas: " . $e->getMessage());
        return $response->setTemplate('error.php');
    }
}

function getIncidentHistory($request, $response, $container) {
    try {
        // Asegurarse de que no hay salida previa
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Prevenir cualquier salida adicional
        ob_start();

        $machineId = $request->getParam("id");
        
        if (!$machineId) {
            $result = [
                'success' => false,
                'message' => "ID de máquina no proporcionado"
            ];
            
            ob_end_clean();
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        // Obtener el modelo Incident
        $incidentModel = $container->get("Incident");
        
        // Obtener los datos
        $result = $incidentModel->getIncidentsByMachine($machineId);

        // Limpiar cualquier salida no deseada
        ob_end_clean();
        
        // Establecer headers
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        
        // Codificar y enviar la respuesta
        $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        if ($json === false) {
            throw new \Exception("Error al codificar JSON: " . json_last_error_msg());
        }
        
        echo $json;
        exit;
        
    } catch (\Exception $e) {
        error_log("Error en getIncidentHistory: " . $e->getMessage());
        
        // Limpiar cualquier salida
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        $error = [
            'success' => false,
            'message' => "Error al cargar el historial: " . $e->getMessage()
        ];
        
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode($error);
        exit;
    }
}