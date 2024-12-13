<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Maintenance.php';
require_once __DIR__ . '/../Models/Machine.php';
require_once __DIR__ . '/../Models/Db.php';

class maintenance_history {
    private $maintenanceModel;
    private $machineModel;
    private $db;

    public function __construct() {
        // Crear la conexión a la base de datos primero
        $this->db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        
        // Luego crear los modelos usando la conexión
        $connection = $this->db->getConnection();
        $this->maintenanceModel = new \App\Models\Maintenance($connection);
        $this->machineModel = new \App\Models\Machine($connection);
    }

    public function index($request, $response) {
        try {
            // Obtener todas las máquinas
            $machines = $this->machineModel->getAllMachine();
            
            // Pasar las máquinas a la vista
            $response->set("machines", $machines);
            
            $response->setTemplate("maintenance_history.php");
            return $response;
        } catch (\Exception $e) {
            error_log("Error en maintenance_history/index: " . $e->getMessage());
            $response->setTemplate("error.php");
            return $response;
        }
    }

    public function getHistory($request, $response) {
        try {
            $machineId = $request->getParam("id");
            error_log("Obteniendo historial para máquina ID: " . $machineId);

            $history = $this->maintenanceModel->getMaintenanceHistory($machineId);
            error_log("Historial obtenido: " . print_r($history, true));

            // Configurar la respuesta como JSON
            $response->setHeader('Content-Type', 'application/json');
            $response->setBody(json_encode($history));
            
            return $response;
            
        } catch (\Exception $e) {
            error_log("Error en getHistory: " . $e->getMessage());
            
            // Configurar respuesta de error
            $response->setHeader('Content-Type', 'application/json');
            $response->setStatus(500);
            $response->setBody(json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]));
            
            return $response;
        }
    }

    public function getMachineInfo($request, $response) {
        try {
            $machineId = $request->getParam("id");
            error_log("Obteniendo información de máquina ID: " . $machineId);

            $machine = $this->machineModel->getMachineById($machineId);
            error_log("Información de máquina obtenida: " . print_r($machine, true));

            // Configurar la respuesta como JSON
            $response->setHeader('Content-Type', 'application/json');
            
            if ($machine) {
                $response->setBody(json_encode([
                    'success' => true,
                    'data' => $machine
                ]));
            } else {
                $response->setStatus(404);
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Máquina no encontrada'
                ]));
            }
            
            return $response;
            
        } catch (\Exception $e) {
            error_log("Error en getMachineInfo: " . $e->getMessage());
            
            // Configurar respuesta de error
            $response->setHeader('Content-Type', 'application/json');
            $response->setStatus(500);
            $response->setBody(json_encode([
                'success' => false,
                'message' => "Error: " . $e->getMessage()
            ]));
            
            return $response;
        }
    }
} 