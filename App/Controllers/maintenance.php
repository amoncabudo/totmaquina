<?php
namespace App\Controllers;

class maintenance {
    public function index($request, $response, $container) {
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        
        $maintenance = new \App\Models\Maintenance($db->getConnection());
        
        $technicians = $maintenance->getAllTechnicians();
        $machines = $maintenance->getAllMachines();
        $allMaintenances = $maintenance->getAllMaintenances();
        
        $response->set("technicians", $technicians);
        $response->set("machines", $machines);
        $response->set("maintenances", $allMaintenances);
        
        $response->setTemplate('maintenance.php');
        return $response;
    }

    public function assignTechnician($request, $response) {
        try {
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['maintenance_id']) || !isset($data['technician_id'])) {
                throw new \Exception("Faltan datos necesarios");
            }
            
            $result = $maintenance->assignTechnician($data['maintenance_id'], $data['technician_id']);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function removeTechnician($request, $response) {
        try {
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['maintenance_id']) || !isset($data['technician_id'])) {
                throw new \Exception("Faltan datos necesarios");
            }
            
            $result = $maintenance->removeTechnician($data['maintenance_id'], $data['technician_id']);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    public function createMaintenance($request, $response) {
        try {
            // Asegurar que no haya salida previa
            if (ob_get_level()) ob_end_clean();
            
            header('Content-Type: application/json');
            
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            // Obtener los datos del formulario
            $machineId = $request->get(INPUT_POST, 'machine_id');
            $scheduledDate = $request->get(INPUT_POST, 'scheduled_date');
            $type = $request->get(INPUT_POST, 'type');
            $frequency = $request->get(INPUT_POST, 'frequency');
            $description = $request->get(INPUT_POST, 'description');
            $techniciansData = $request->get(INPUT_POST, 'technicians_data');
            
            // Log de datos recibidos
            error_log("Datos recibidos en createMaintenance:");
            error_log("Machine ID: " . $machineId);
            error_log("Scheduled Date: " . $scheduledDate);
            error_log("Type: " . $type);
            error_log("Frequency: " . $frequency);
            error_log("Description: " . $description);
            error_log("Technicians Data: " . $techniciansData);
            
            // Validar datos requeridos
            if (!$machineId || !$scheduledDate || !$type || !$frequency || !$description) {
                error_log("Error: Faltan campos requeridos");
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos los campos son obligatorios'
                ]);
                exit;
            }
            
            // Decodificar los técnicos asignados
            $technicians = json_decode($techniciansData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Error decodificando técnicos: " . json_last_error_msg());
                $technicians = [];
            }
            error_log("Técnicos decodificados: " . print_r($technicians, true));
            
            // Preparar los datos para inserción
            $maintenanceData = [
                'machine_id' => $machineId,
                'scheduled_date' => $scheduledDate,
                'type' => $type,
                'frequency' => $frequency,
                'description' => $description,
                'status' => 'pending',
                'technicians' => $technicians
            ];
            
            // Insertar el mantenimiento
            $result = $maintenance->addMaintenance($maintenanceData);
            
            if ($result) {
                error_log("Mantenimiento creado exitosamente");
                echo json_encode([
                    'success' => true,
                    'message' => 'Mantenimiento registrado exitosamente'
                ]);
            } else {
                error_log("Error al crear el mantenimiento");
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al registrar el mantenimiento'
                ]);
            }
            exit;
            
        } catch (\Exception $e) {
            error_log("Error en createMaintenance: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
}