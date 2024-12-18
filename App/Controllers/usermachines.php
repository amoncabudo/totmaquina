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

            error_log("=== INICIO updateIncidentStatus en Controller ===");
            error_log("Datos recibidos:");
            error_log("- ID Incidencia: " . $incidentId);
            error_log("- Estado: " . $status);
            error_log("- ID Usuario: " . $userId);

            if (empty($incidentId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Verificar que la incidencia pertenece al técnico
            $machineModel = $container->get("Machine");
            $incidents = $machineModel->getIncidentsByTechnicianAndMachine($userId, null);
            
            error_log("Incidencias del técnico encontradas: " . count($incidents));
            
            $isAssigned = false;
            foreach ($incidents as $incident) {
                error_log("Comparando incidencia " . $incident['id'] . " con estado " . $incident['status']);
                if ($incident['id'] == $incidentId) {
                    $isAssigned = true;
                    error_log("Incidencia encontrada y asignada al técnico");
                    break;
                }
            }

            if (!$isAssigned) {
                throw new \Exception("No tienes permiso para modificar esta incidencia");
            }

            // Actualizar el estado
            $incidentModel = $container->get("Incident");
            error_log("Llamando a updateIncidentStatus del modelo");
            
            try {
                $result = $incidentModel->updateIncidentStatus($incidentId, $status);
                error_log("Resultado de la actualización: " . ($result ? "exitoso" : "fallido"));
                
                // Verificar el estado después de la actualización
                $updatedIncident = $incidentModel->getIncidentById($incidentId);
                error_log("Estado después de la actualización: " . ($updatedIncident['status'] ?? 'NULL'));
                
                if ($updatedIncident['status'] !== $status) {
                    throw new \Exception("El estado no coincide después de la actualización");
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente',
                    'newStatus' => $updatedIncident['status']
                ]);
            } catch (\Exception $e) {
                error_log("Error durante la actualización: " . $e->getMessage());
                throw $e;
            }

            error_log("=== FIN updateIncidentStatus en Controller ===");
            exit;

        } catch (\Exception $e) {
            error_log("Error en updateIncidentStatus: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'details' => 'Error en el controlador: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function updateMaintenanceStatus(Request $request, Response $response, Container $container) {
        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
                throw new \Exception("No autorizado");
            }

            $maintenanceId = $request->get(INPUT_POST, "maintenance_id");
            $status = $request->get(INPUT_POST, "status");
            $userId = $_SESSION["user"]["id"];

            error_log("=== INICIO updateMaintenanceStatus en Controller ===");
            error_log("Datos recibidos:");
            error_log("- ID Mantenimiento: " . $maintenanceId);
            error_log("- Estado: " . $status);
            error_log("- ID Usuario: " . $userId);

            if (empty($maintenanceId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Validar el estado
            $validStatuses = ['pending', 'in_progress', 'completed'];
            if (!in_array($status, $validStatuses)) {
                throw new \Exception("Estado no válido");
            }

            // Obtener el modelo de mantenimiento
            $maintenanceModel = $container->get("Maintenance");

            // Verificar que el mantenimiento existe y que el técnico está asignado
            if (!$maintenanceModel->isMaintenanceAssignedToTechnician($maintenanceId, $userId)) {
                throw new \Exception("No tienes permiso para modificar este mantenimiento");
            }

            // Actualizar el estado
            $result = $maintenanceModel->updateMaintenanceStatus($maintenanceId, $status);

            if (!$result) {
                throw new \Exception("Error al actualizar el estado del mantenimiento");
            }

            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
            exit;

        } catch (\Exception $e) {
            error_log("Error en updateMaintenanceStatus: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
} 