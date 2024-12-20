<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class usermachines {
    // This method is responsible for displaying the machines assigned to the logged-in user
    public function index(Request $request, Response $response, Container $container) {
        // Check if the user is logged in
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            // Redirect to login page if the user is not logged in
            $response->redirect("Location: /login");
            return $response;
        }

        // Get the user ID from the session
        $userId = $_SESSION["user"]["id"];
        
        // Retrieve the machines assigned to the user (technician)
        $machineModel = $container->get("Machine");
        $assignedMachines = $machineModel->getMachinesByTechnician($userId);

        // Prepare data for each assigned machine
        $machinesData = [];
        foreach ($assignedMachines as $machine) {
            $machineId = $machine['id'];
            
            // Retrieve incidents assigned to the technician for this machine
            $incidents = $machineModel->getIncidentsByTechnicianAndMachine($userId, $machineId);
            
            // Retrieve maintenance assigned to the technician for this machine
            $assignedMaintenance = $machineModel->getMaintenanceByTechnician($userId, $machineId);
            
            // Retrieve the maintenance history of the machine
            $maintenance = $machineModel->getMaintenanceHistory($machineId);
            
            // Retrieve all incidents of the machine
            $allIncidents = $machineModel->getMachineIncidents($machineId);

            // Store machine data for the view
            $machinesData[] = [
                'machine' => $machine,
                'assigned_incidents' => $incidents,
                'assigned_maintenance' => $assignedMaintenance,
                'maintenance_history' => $maintenance,
                'all_incidents' => $allIncidents
            ];
        }

        // Pass the machines data to the response
        $response->set("machinesData", $machinesData);
        // Set the template to render
        $response->SetTemplate("usermachines.php");
        return $response;
    }

    // This method is responsible for updating the incident status
    public function updateIncidentStatus(Request $request, Response $response, Container $container) {
        // Set the content type to JSON
        header('Content-Type: application/json');

        try {
            // Check if the user is logged in
            if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
                throw new \Exception("No autorizado");  // Unauthorized access
            }

            // Get the incident ID and status from the request
            $incidentId = $request->get(INPUT_POST, "incident_id");
            $status = $request->get(INPUT_POST, "status");
            $userId = $_SESSION["user"]["id"];

            // Log the received data for debugging purposes
            error_log("=== INICIO updateIncidentStatus en Controller ===");
            error_log("Datos recibidos:");
            error_log("- ID Incidencia: " . $incidentId);
            error_log("- Estado: " . $status);
            error_log("- ID Usuario: " . $userId);

            // Check if required data is missing
            if (empty($incidentId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Check if the incident belongs to the technician
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

            // If the technician is not assigned to the incident, throw an error
            if (!$isAssigned) {
                throw new \Exception("No tienes permiso para modificar esta incidencia");
            }

            // Update the incident status in the model
            $incidentModel = $container->get("Incident");
            error_log("Llamando a updateIncidentStatus del modelo");
            
            try {
                $result = $incidentModel->updateIncidentStatus($incidentId, $status);
                error_log("Resultado de la actualización: " . ($result ? "exitoso" : "fallido"));
                
                // Retrieve the updated incident status for verification
                $updatedIncident = $incidentModel->getIncidentById($incidentId);
                error_log("Estado después de la actualización: " . ($updatedIncident['status'] ?? 'NULL'));
                
                // Check if the status was correctly updated
                if ($updatedIncident['status'] !== $status) {
                    throw new \Exception("El estado no coincide después de la actualización");
                }
                
                // Respond with success message
                echo json_encode([
                    'success' => true,
                    'message' => 'Estado actualizado correctamente',
                    'newStatus' => $updatedIncident['status']
                ]);
            } catch (\Exception $e) {
                // Log any errors during the update
                error_log("Error durante la actualización: " . $e->getMessage());
                throw $e;
            }

            error_log("=== FIN updateIncidentStatus en Controller ===");
            exit;

        } catch (\Exception $e) {
            // Handle any errors in the process
            error_log("Error en updateIncidentStatus: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);  // Send error response code
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'details' => 'Error en el controlador: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    // This method is responsible for updating the maintenance status
    public function updateMaintenanceStatus(Request $request, Response $response, Container $container) {
        // Set the content type to JSON
        header('Content-Type: application/json');

        try {
            // Check if the user is logged in
            if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
                throw new \Exception("No autorizado");  // Unauthorized access
            }

            // Get the maintenance ID and status from the request
            $maintenanceId = $request->get(INPUT_POST, "maintenance_id");
            $status = $request->get(INPUT_POST, "status");
            $userId = $_SESSION["user"]["id"];

            // Log the received data for debugging purposes
            error_log("=== INICIO updateMaintenanceStatus en Controller ===");
            error_log("Datos recibidos:");
            error_log("- ID Mantenimiento: " . $maintenanceId);
            error_log("- Estado: " . $status);
            error_log("- ID Usuario: " . $userId);

            // Check if required data is missing
            if (empty($maintenanceId) || empty($status)) {
                throw new \Exception("Faltan datos requeridos");
            }

            // Validate the status
            $validStatuses = ['pending', 'in_progress', 'completed'];
            if (!in_array($status, $validStatuses)) {
                throw new \Exception("Estado no válido");
            }

            // Get the maintenance model from the container
            $maintenanceModel = $container->get("Maintenance");

            // Verify that the maintenance exists and that the technician is assigned
            if (!$maintenanceModel->isMaintenanceAssignedToTechnician($maintenanceId, $userId)) {
                throw new \Exception("No tienes permiso para modificar este mantenimiento");
            }

            // Update the maintenance status
            $result = $maintenanceModel->updateMaintenanceStatus($maintenanceId, $status);

            // If update fails, throw an exception
            if (!$result) {
                throw new \Exception("Error al actualizar el estado del mantenimiento");
            }

            // Respond with success message
            echo json_encode([
                'success' => true,
                'message' => 'Estado actualizado correctamente'
            ]);
            exit;

        } catch (\Exception $e) {
            // Handle any errors in the process
            error_log("Error en updateMaintenanceStatus: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);  // Send error response code
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
}
