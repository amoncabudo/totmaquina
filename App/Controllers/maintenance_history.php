<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Maintenance.php';
require_once __DIR__ . '/../Models/Machine.php';
require_once __DIR__ . '/../Models/Db.php';

class MaintenanceHistoryController {
    private $maintenanceModel;
    private $machineModel;
    private $db;

    public function __construct() {
        $this->db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $connection = $this->db->getConnection();

        if (!$connection) {
            throw new \Exception("No es pot establir la connexió amb la base de dades.");
        }

        $this->maintenanceModel = new \App\Models\Maintenance($connection);
        $this->machineModel = new \App\Models\Machine($connection);
    }

    public function index($request, $response) {
        try {
            // Obtenir totes les màquines
            $machines = $this->machineModel->getAllMachine();
            if (!$machines) {
                $machines = [];
            }

            // Passar les màquines a la vista
            $response->set("machines", $machines);
            $response->setTemplate("maintenance_history.php");
        } catch (\Exception $e) {
            error_log("Error en index: " . $e->getMessage());
            $response->setStatus(500);
            $response->setTemplate("error.php");
        }
        
        return $response;
    }

    public function getHistory($request, $response) {
        try {
            $machineId = $request->getParam("id");
            if (!$machineId) {
                throw new \Exception("ID de màquina no proporcionat.");
            }

            error_log("Obtenint historial per a màquina ID: " . $machineId);

            $history = $this->maintenanceModel->getMaintenanceHistory($machineId);
            if (!$history) {
                $history = [];
            }

            // Configurar la resposta com a JSON
            $response->setHeader('Content-Type', 'application/json');
            $response->setBody(json_encode([
                'success' => true,
                'data' => $history
            ]));
        } catch (\Exception $e) {
            error_log("Error en getHistory: " . $e->getMessage());
            $response->setHeader('Content-Type', 'application/json');
            $response->setStatus(500);
            $response->setBody(json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]));
        }
        
        return $response;
    }

    public function getMachineInfo($request, $response) {
        try {
            $machineId = $request->getParam("id");
            if (!$machineId) {
                throw new \Exception("ID de màquina no proporcionat.");
            }

            error_log("Obtenint informació de màquina ID: " . $machineId);

            $machine = $this->machineModel->getMachineById($machineId);
            if (!$machine) {
                throw new \Exception("Màquina no trobada.");
            }

            // Configurar la resposta com a JSON
            $response->setHeader('Content-Type', 'application/json');
            $response->setBody(json_encode([
                'success' => true,
                'data' => $machine
            ]));
        } catch (\Exception $e) {
            error_log("Error en getMachineInfo: " . $e->getMessage());
            $response->setHeader('Content-Type', 'application/json');
            $response->setStatus(500);
            $response->setBody(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
        }
        
        return $response;
    }
}
