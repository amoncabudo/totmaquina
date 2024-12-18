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
}