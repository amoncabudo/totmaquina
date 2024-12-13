<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class usermachines {
    public function index(Request $request, Response $response, Container $container) {
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            $response->redirect("Location: /login");
            return $response;
        }

        $userId = $_SESSION["user"]["id"];
        
        // Obtener las máquinas asignadas al usuario
        $machineModel = $container->get("Machine");
        $assignedMachines = $machineModel->getMachinesByTechnician($userId);

        // Para cada máquina, obtener sus incidencias y mantenimientos
        $machinesData = [];
        foreach ($assignedMachines as $machine) {
            $machineId = $machine['id'];
            
            // Obtener incidencias asignadas al técnico para esta máquina
            $incidents = $machineModel->getIncidentsByTechnicianAndMachine($userId, $machineId);
            
            // Obtener mantenimientos asignados al técnico para esta máquina
            $assignedMaintenance = $machineModel->getMaintenanceByTechnician($userId, $machineId);
            
            // Obtener historial de mantenimiento de la máquina
            $maintenance = $machineModel->getMaintenanceHistory($machineId);
            
            // Obtener todas las incidencias de la máquina
            $allIncidents = $machineModel->getMachineIncidents($machineId);

            $machinesData[] = [
                'machine' => $machine,
                'assigned_incidents' => $incidents,
                'assigned_maintenance' => $assignedMaintenance,
                'maintenance_history' => $maintenance,
                'all_incidents' => $allIncidents
            ];
        }

        $response->set("machinesData", $machinesData);
        $response->SetTemplate("usermachines.php");
        return $response;
    }

    public function updateIncidentStatus(Request $request, Response $response, Container $container) {
        // Establecer el tipo de contenido como JSON
        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
                throw new \Exception("No autorizado");
            }

            $incidentId = $request->get(INPUT_POST, "incident_id");
            $status = $request->get(INPUT_POST, "status");
            $userId = $_SESSION["user"]["id"];

            if (empty($incidentId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Verificar que la incidencia pertenece al técnico
            $machineModel = $container->get("Machine");
            $incidents = $machineModel->getIncidentsByTechnicianAndMachine($userId, null);
            $isAssigned = false;
            foreach ($incidents as $incident) {
                if ($incident['id'] == $incidentId) {
                    $isAssigned = true;
                    break;
                }
            }

            if (!$isAssigned) {
                throw new \Exception("No tienes permiso para modificar esta incidencia");
            }

            // Actualizar el estado
            $incidentModel = $container->get("Incident");
            $result = $incidentModel->updateIncidentStatus($incidentId, $status);

            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
            exit;

        } catch (\Exception $e) {
            error_log("Error en updateIncidentStatus: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function updateMaintenanceStatus(Request $request, Response $response, Container $container) {
        // Establecer el tipo de contenido como JSON
        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
                throw new \Exception("No autorizado");
            }

            $maintenanceId = $request->get(INPUT_POST, "maintenance_id");
            $status = $request->get(INPUT_POST, "status");
            $userId = $_SESSION["user"]["id"];

            if (empty($maintenanceId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Verificar que el mantenimiento pertenece al técnico
            $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
            $maintenanceModel = new \App\Models\Maintenance($db->getConnection());
            
            // Verificar que el mantenimiento existe y que el técnico está asignado
            $sql = "SELECT m.id 
                   FROM Maintenance m 
                   INNER JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id 
                   WHERE m.id = ? AND mt.technician_id = ?";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute([$maintenanceId, $userId]);
            
            if (!$stmt->fetch()) {
                throw new \Exception("No tienes permiso para modificar este mantenimiento");
            }

            // Actualizar el estado
            $result = $maintenanceModel->updateMaintenanceStatus($maintenanceId, $status);

            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
            exit;

        } catch (\Exception $e) {
            error_log("Error en updateMaintenanceStatus: " . $e->getMessage());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
} 
