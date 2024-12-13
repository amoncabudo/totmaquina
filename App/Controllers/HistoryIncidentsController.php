<?php
namespace App\Controllers;

class HistoryIncidentsController {
    public function index($request, $response) {
        try {
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $model = new \App\Models\HistoryIncidents($db->getConnection());
            
            // Obtener la lista de máquinas para el selector
            $machines = $model->getAllMachines();
            $response->set("machines", $machines);
            
            $response->setTemplate("history.php");
            return $response;
        } catch (\Exception $e) {
            error_log("Error en HistoryIncidentsController::index: " . $e->getMessage());
            // Mostrar un mensaje de error en la vista
            $response->set("error", "Error al cargar la página: " . $e->getMessage());
            $response->setTemplate("history.php");
            return $response;
        }
    }

    public function getHistory($request, $response) {
        try {
            $machineId = $request->getParam("id");
            if (!$machineId) {
                throw new \Exception("ID de máquina no proporcionado");
            }

            $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
            $model = new \App\Models\HistoryIncidents($db->getConnection());
            
            $historial = $model->getIncidentHistory($machineId);
            
            // Configurar la respuesta como JSON
            $response->setHeader("Content-Type: application/json");
            
            // Si no hay resultados, devolver un array vacío en lugar de null
            if (empty($historial)) {
                $response->setBody(json_encode([]));
            } else {
                $response->setBody(json_encode($historial));
            }
            
            return $response;
        } catch (\Exception $e) {
            error_log("Error en HistoryIncidentsController::getHistory: " . $e->getMessage());
            
            // Configurar respuesta de error
            $response->setHeader("Content-Type: application/json");
            $response->setStatus(500);
            $response->setBody(json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]));
            
            return $response;
        }
    }
} 