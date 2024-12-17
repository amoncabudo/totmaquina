<?php
namespace App\Controllers;

class maintenance_history {
    private $maintenanceModel;
    private $machineModel;
    private $db;

    public function __construct() {
        try {
            // Crear la conexión a la base de datos primero
            $this->db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            
            // Luego crear los modelos usando la conexión
            $connection = $this->db->getConnection();
            $this->maintenanceModel = new \App\Models\Maintenance($connection);
            $this->machineModel = new \App\Models\Machine($connection);
        } catch (\Exception $e) {
            error_log("Error en constructor de maintenance_history: " . $e->getMessage());
            throw $e;
        }
    }

    public function index($request, $response) {
        try {
            // Obtener todas las máquinas
            $machines = $this->machineModel->getAllMachine();
            
            // Pasar las máquinas a la vista
            $response->set("machines", $machines);
            
            return $response->setTemplate("maintenance_history.php");
        } catch (\Exception $e) {
            error_log("Error en maintenance_history/index: " . $e->getMessage());
            return $response->setTemplate("error.php");
        }
    }

    public function getHistory($request, $response) {
        try {
            // Prevenir cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Obtener el ID de la máquina
            $machineId = $request->getParam("id");
            if (!$machineId) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => "ID de máquina no proporcionado"
                ]);
                exit;
            }

            // Obtener el historial
            $history = $this->maintenanceModel->getMaintenanceHistory($machineId);
            
            // Si no hay historial, devolver array vacío
            if (!$history) {
                $history = [];
            }

            // Asegurar que los datos son serializables
            $history = array_map(function($record) {
                return array_map(function($value) {
                    return is_string($value) ? utf8_encode($value) : $value;
                }, $record);
            }, $history);

            // Enviar respuesta JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'history' => $history
            ]);
            exit;
            
        } catch (\Exception $e) {
            error_log("Error en getHistory: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Limpiar cualquier salida anterior
            while (ob_get_level()) {
                ob_end_clean();
            }

            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => "Error al cargar el historial: " . $e->getMessage()
            ]);
            exit;
        }
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
            $response->setHeader('Content-Type', 'application/json');
            $response->setStatus(500);
            $response->setBody(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
            
            return $response;
        }
    }
} 
