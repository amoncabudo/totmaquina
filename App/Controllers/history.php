<?php
function history($request, $response, $container) {
    try {
        error_log("Iniciando función history");
        $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
        $model = new \App\Models\HistoryIncidents($db->getConnection());
        
        // Obtener la lista de máquinas
        $machines = $model->getAllMachines();
        error_log("Máquinas obtenidas: " . print_r($machines, true));
        
        // Pasar los datos a la vista
        $response->set("machines", $machines);
        $response->set("title", "Historial de Incidencias");
        
        $response->setTemplate('history.php');
        error_log("Vista history.php configurada correctamente");
        return $response;
    } catch (\Exception $e) {
        error_log("Error en history: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        $response->set("error", "Error al cargar las máquinas: " . $e->getMessage());
        $response->setTemplate('history.php');
        return $response;
    }
}

function getIncidentHistory($request, $response, $container) {
    try {
        error_log("Iniciando getIncidentHistory");
        $machineId = $request->getParam("id");
        error_log("ID de máquina recibido: " . $machineId);
        
        if (!$machineId) {
            throw new \Exception("ID de máquina no proporcionado");
        }

        $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
        $model = new \App\Models\HistoryIncidents($db->getConnection());
        
        // Obtener la información de la máquina
        error_log("Obteniendo información de la máquina...");
        $machineInfo = $model->getMachineInfo($machineId);
        error_log("Información de máquina obtenida: " . print_r($machineInfo, true));
        
        // Obtener el historial de incidencias
        error_log("Obteniendo historial de incidencias...");
        $history = $model->getIncidentHistory($machineId);
        error_log("Historial obtenido: " . print_r($history, true));
        
        // Preparar la respuesta
        $response->setHeader("Content-Type", "application/json");
        $response->setHeader("Cache-Control", "no-cache, no-store, must-revalidate");
        $response->setHeader("Pragma", "no-cache");
        $response->setHeader("Expires", "0");
        
        $jsonResponse = [
            'success' => true,
            'machine' => $machineInfo,
            'data' => $history
        ];
        
        error_log("Preparando respuesta JSON: " . json_encode($jsonResponse));
        $response->setBody(json_encode($jsonResponse));
        
        return $response;
    } catch (\Exception $e) {
        error_log("Error en getIncidentHistory: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        $response->setHeader("Content-Type", "application/json");
        $response->setStatus(500);
        $response->setBody(json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]));
        return $response;
    }
}