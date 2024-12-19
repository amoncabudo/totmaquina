<?php

// Function to load the incidents page
function incidents($request, $response, $container) {
    try {
        // Debug log for tracking
        error_log("=== DEBUG: Loading incidents page ===");
        
        // Database connection
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Get data for the view: technicians, machines, and incidents
        $technicians = $incident->getAllTechnicians();
        $machines = $incident->getAllMachines();
        $allIncidents = $incident->getAllIncidents();
        
        // Debug logs to confirm the data
        error_log("Technicians loaded: " . count($technicians));
        error_log("Machines loaded: " . count($machines));
        error_log("Incidents loaded: " . count($allIncidents));
        
        // Pass the data to the view
        $response->set("technicians", $technicians);
        $response->set("machines", $machines);
        $response->set("incidents", $allIncidents);
        
        // Set the template for incidents page
        $response->setTemplate('incidents.php');
        return $response;
    } catch (\Exception $e) {
        // Log any errors during the process
        error_log("Error loading incidents page: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        // Store the error message in the session and redirect
        $_SESSION['error_message'] = "Error loading the page: " . $e->getMessage();
        header('Location: /');
        exit;
    }
}

// Function to create a new incident
function createIncident($request, $response, $container) {
    try {
        // Debug log for tracking
        error_log("=== DEBUG: Starting incident creation ===");
        
        // Validate if the user is authenticated
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            throw new \Exception("Unauthorized");
        }

        // Database connection
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $incident = new \App\Models\Incident($db->getConnection());
        
        // Get and validate the data from the form
        $description = $request->get(INPUT_POST, "description");
        $priority = $request->get(INPUT_POST, "priority");
        $machineId = $request->get(INPUT_POST, "machine_id");
        $technicianId = $request->get(INPUT_POST, "responsible_technician_id");

        // Debug logs for form data
        error_log("Form data:");
        error_log("- Description: " . $description);
        error_log("- Priority: " . $priority);
        error_log("- Machine ID: " . $machineId);
        error_log("- Technician ID: " . $technicianId);

        // Basic validations
        if (empty($description)) {
            throw new \Exception("Description is required");
        }
        if (empty($machineId)) {
            throw new \Exception("You must select a machine");
        }
        if (empty($technicianId)) {
            throw new \Exception("You must select a technician");
        }

        // Prepare the data for the model
        $data = [
            'description' => $description,
            'priority' => $priority,
            'machine_id' => $machineId,
            'responsible_technician_id' => $technicianId
        ];
        
        // Debug log for prepared data
        error_log("Prepared data for model: " . print_r($data, true));
        
        // Create the incident
        $incidentId = $incident->create($data);
        
        if ($incidentId) {
            // Log success and fetch the new incident data
            error_log("Incident created successfully with ID: " . $incidentId);
            
            // Retrieve the new incident details
            $newIncident = $incident->getIncidentById($incidentId);
            error_log("New incident details: " . print_r($newIncident, true));
            
            // Store success message in session and redirect
            $_SESSION['success_message'] = "Incident created successfully";
            header('Location: /incidents');
            exit;
        } else {
            throw new \Exception("Error creating the incident");
        }
        
    } catch (\Exception $e) {
        // Log any errors during the incident creation process
        error_log("=== ERROR in createIncident ===");
        error_log($e->getMessage());
        error_log($e->getTraceAsString());
        
        // Store the error message in the session and redirect
        $_SESSION['error_message'] = $e->getMessage();
        header('Location: /incidents');
        exit;
    }
}
