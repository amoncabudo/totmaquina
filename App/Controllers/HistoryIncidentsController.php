<?php
namespace App\Controllers;

class HistoryIncidentsController {
    
    // Function to load the history page
    public function index($request, $response) {
        try {
            // Create a new database connection
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $model = new \App\Models\HistoryIncidents($db->getConnection());
            
            // Get the list of machines for the selector
            $machines = $model->getAllMachines();
            $response->set("machines", $machines);
            
            // Set the template for the history page
            $response->setTemplate("history.php");
            return $response;
        } catch (\Exception $e) {
            // Log the error if something goes wrong
            error_log("Error in HistoryIncidentsController::index: " . $e->getMessage());
            
            // Set the error message to be shown in the view
            $response->set("error", "Error loading the page: " . $e->getMessage());
            $response->setTemplate("history.php");
            return $response;
        }
    }

    // Function to get the incident history of a machine
    public function getHistory($request, $response) {
        try {
            // Get the machine ID from the request
            $machineId = $request->getParam("id");
            
            // Throw an exception if the machine ID is not provided
            if (!$machineId) {
                throw new \Exception("Machine ID not provided");
            }

            // Create a new database connection
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $model = new \App\Models\HistoryIncidents($db->getConnection());
            
            // Get the incident history for the given machine
            $historial = $model->getIncidentHistory($machineId);
            
            // Set the response to return JSON
            $response->setHeader("Content-Type: application/json");
            
            // If no results, return an empty array instead of null
            if (empty($historial)) {
                $response->setBody(json_encode([]));
            } else {
                $response->setBody(json_encode($historial));
            }
            
            return $response;
        } catch (\Exception $e) {
            // Log the error if something goes wrong
            error_log("Error in HistoryIncidentsController::getHistory: " . $e->getMessage());
            
            // Set the error response with status 500
            $response->setHeader("Content-Type: application/json");
            $response->setStatus(500);
            $response->setBody(json_encode([
                "error" => true,
                "message" => $e->getMessage()
            ]));
            
            return $response;
        }
    }
}
