<?php
namespace App\Controllers;

class HistoryIncidentsController {
    private function getModel() {
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        return new \App\Models\HistoryIncidents($db->getConnection());
    }

    public function index($request, $response) {
        try {
            error_log("Iniciando HistoryIncidentsController::index");
            $model = $this->getModel();
            
            // Obtener lista de máquinas
            $machines = $model->getAllMachines();
            if (empty($machines)) {
                $response->set("error", "No se encontraron máquinas disponibles.");
            } else {
                $response->set("machines", $machines);
            }
            
            $response->setTemplate("history.php");
            return $response;
        } catch (\Exception $e) {
            error_log("Error en HistoryIncidentsController::index: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response->set("error", "Error al cargar la página: " . $e->getMessage());
            $response->setTemplate("history.php");
            $response->setStatus(500);
            return $response;
        }
    }

    public function getHistory($request, $response) {
        try {
            error_log("Iniciando HistoryIncidentsController::getHistory");
            error_log("Parámetros recibidos: " . json_encode($request->getParams()));

            $machineId = $request->getParam("id");
            if (!$machineId) {
                throw new \Exception("ID de máquina no proporcionado");
            }

            $model = $this->getModel();
            $historial = $model->getIncidentHistory($machineId);

            $response->setHeader("Content-Type", "application/json");
            $response->setBody(json_encode($historial ?? []));
            return $response;
        } catch (\Exception $e) {
            error_log("Error en HistoryIncidentsController::getHistory: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response->setHeader("Content-Type", "application/json");
            $response->setStatus(500);
            $response->setBody(json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]));
            return $response;
        }
    }
}
