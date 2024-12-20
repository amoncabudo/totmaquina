<?php
namespace App\Controllers;

class maintenance {
    // This method loads the maintenance page with technicians, machines, and all maintenance records
    public function index($request, $response, $container) {
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        
        $maintenance = new \App\Models\Maintenance($db->getConnection());
        
        // Get all technicians, machines, and maintenance records
        $technicians = $maintenance->getAllTechnicians();
        $machines = $maintenance->getAllMachines();
        $allMaintenances = $maintenance->getAllMaintenances();
        
        // Set the data for use in the view
        $response->set("technicians", $technicians);
        $response->set("machines", $machines);
        $response->set("maintenances", $allMaintenances);
        
        // Set the template for the page
        $response->setTemplate('maintenance.php');
        return $response;
    }

    // This method assigns a technician to a maintenance record
    public function assignTechnician($request, $response) {
        try {
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            // Get the data from the request body (in JSON format)
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Check if necessary data is provided
            if (!isset($data['maintenance_id']) || !isset($data['technician_id'])) {
                throw new \Exception("Missing necessary data");
            }
            
            // Assign the technician to the maintenance
            $result = $maintenance->assignTechnician($data['maintenance_id'], $data['technician_id']);
            
            // Return a success response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    // This method removes a technician from a maintenance record
    public function removeTechnician($request, $response) {
        try {
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            // Get the data from the request body (in JSON format)
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Check if necessary data is provided
            if (!isset($data['maintenance_id']) || !isset($data['technician_id'])) {
                throw new \Exception("Missing necessary data");
            }
            
            // Remove the technician from the maintenance
            $result = $maintenance->removeTechnician($data['maintenance_id'], $data['technician_id']);
            
            // Return a success response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
            
        } catch (\Exception $e) {
            // Return an error response if an exception occurs
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }

    // This method creates a new maintenance record
    public function createMaintenance($request, $response) {
        try {
            // Ensure no output has been sent yet
            if (ob_get_level()) ob_end_clean();
            
            // Set the response content type to JSON
            header('Content-Type: application/json');
            
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $maintenance = new \App\Models\Maintenance($db->getConnection());
            
            // Get form data
            $machineId = $request->get(INPUT_POST, 'machine_id');
            $scheduledDate = $request->get(INPUT_POST, 'scheduled_date');
            $type = $request->get(INPUT_POST, 'type');
            $frequency = $request->get(INPUT_POST, 'frequency');
            $description = $request->get(INPUT_POST, 'description');
            $techniciansData = $request->get(INPUT_POST, 'technicians_data');
            
            // Log received data for debugging
            error_log("Received data in createMaintenance:");
            error_log("Machine ID: " . $machineId);
            error_log("Scheduled Date: " . $scheduledDate);
            error_log("Type: " . $type);
            error_log("Frequency: " . $frequency);
            error_log("Description: " . $description);
            error_log("Technicians Data: " . $techniciansData);
            
            // Validate required fields
            if (!$machineId || !$scheduledDate || !$type || !$frequency || !$description) {
                error_log("Error: Missing required fields");
                echo json_encode([
                    'success' => false,
                    'message' => 'All fields are required'
                ]);
                exit;
            }
            
            // Decode the assigned technicians data
            $technicians = json_decode($techniciansData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("Error decoding technicians: " . json_last_error_msg());
                $technicians = [];
            }
            error_log("Decoded technicians: " . print_r($technicians, true));
            
            // Prepare the maintenance data for insertion
            $maintenanceData = [
                'machine_id' => $machineId,
                'scheduled_date' => $scheduledDate,
                'type' => $type,
                'frequency' => $frequency,
                'description' => $description,
                'status' => 'pending',
                'technicians' => $technicians
            ];
            
            // Insert the maintenance record
            $result = $maintenance->addMaintenance($maintenanceData);
            
            // Return success or failure response
            if ($result) {
                error_log("Maintenance created successfully");
                echo json_encode([
                    'success' => true,
                    'message' => 'Maintenance successfully registered'
                ]);
            } else {
                error_log("Error creating maintenance");
                echo json_encode([
                    'success' => false,
                    'message' => 'Error registering maintenance'
                ]);
            }
            exit;
            
        } catch (\Exception $e) {
            // Log error details and return a failure response
            error_log("Error in createMaintenance: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
}
