<?php

// Function to display the machine history page
function history($request, $response, $container) {
    try {
        // Use the Machine model from the container
        $model = $container->get("Machine");
        
        // Get the list of machines
        $machines = $model->getAllMachine();
        
        // Pass the data to the view
        $response->set("machines", $machines);
        $response->set("title", "Incident History");
        
        // Return the view template for history
        return $response->setTemplate('history.php');
    } catch (\Exception $e) {
        // Log the error if something goes wrong
        error_log("Error in history: " . $e->getMessage());
        
        // Set the error message and return the error page template
        $response->set("error", "Error loading machines: " . $e->getMessage());
        return $response->setTemplate('error.php');
    }
}

// Function to get incident history by machine
function getIncidentHistory($request, $response, $container) {
    try {
        // Make sure no previous output has been sent
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Prevent any additional output
        ob_start();

        // Get the machine ID from the request parameters
        $machineId = $request->getParam("id");
        
        // If machine ID is not provided, return an error response
        if (!$machineId) {
            $result = [
                'success' => false,
                'message' => "Machine ID not provided"
            ];
            
            ob_end_clean();
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        }

        // Get the Incident model from the container
        $incidentModel = $container->get("Incident");
        
        // Get the incident data by machine ID
        $result = $incidentModel->getIncidentsByMachine($machineId);

        // Clean any unwanted output
        ob_end_clean();
        
        // Set response headers
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json');
        
        // Encode and send the response as JSON
        $json = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        // If JSON encoding fails, throw an exception
        if ($json === false) {
            throw new \Exception("Error encoding JSON: " . json_last_error_msg());
        }
        
        echo $json;
        exit;
        
    } catch (\Exception $e) {
        // Log the error if something goes wrong
        error_log("Error in getIncidentHistory: " . $e->getMessage());
        
        // Clean any previous output
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Set the error message and send a 500 Internal Server Error response
        $error = [
            'success' => false,
            'message' => "Error loading incident history: " . $e->getMessage()
        ];
        
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode($error);
        exit;
    }
}
