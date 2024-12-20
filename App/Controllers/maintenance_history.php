<?php
namespace App\Controllers;

class maintenance_history {
    private $maintenanceModel; // Declare a private variable to hold the Maintenance model
    private $machineModel;     // Declare a private variable to hold the Machine model
    private $db;               // Declare a private variable for the database connection

    public function __construct() {
        try {
            // Create a connection to the database first
            $this->db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            
            // Then create the models using the connection
            $connection = $this->db->getConnection();
            $this->maintenanceModel = new \App\Models\Maintenance($connection); // Instantiate the Maintenance model
            $this->machineModel = new \App\Models\Machine($connection);         // Instantiate the Machine model
        } catch (\Exception $e) {
            // If an error occurs while creating the connection or models, log the error and throw the exception
            error_log("Error in maintenance_history constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function index($request, $response) {
        try {
            // Fetch all machines from the Machine model
            $machines = $this->machineModel->getAllMachine();
            
            // Pass the machines data to the view for rendering
            $response->set("machines", $machines);
            
            // Set the template for rendering the response
            return $response->setTemplate("maintenance_history.php");
        } catch (\Exception $e) {
            // If an error occurs, log the error and return the error page
            error_log("Error in maintenance_history/index: " . $e->getMessage());
            return $response->setTemplate("error.php");
        }
    }

    public function getHistory($request, $response) {
        try {
            // Prevent any previous output from interfering with the response
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Get the machine ID from the request parameters
            $machineId = $request->getParam("id");
            if (!$machineId) {
                // If no machine ID is provided, return a 400 error with a JSON response
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => "Machine ID not provided"
                ]);
                exit;
            }

            // Get the maintenance history for the machine using the maintenance model
            $history = $this->maintenanceModel->getMaintenanceHistory($machineId);
            
            // If no history is found, return an empty array
            if (!$history) {
                $history = [];
            }

            // Ensure that the data is serializable (e.g., encode strings as UTF-8)
            $history = array_map(function($record) {
                return array_map(function($value) {
                    return is_string($value) ? utf8_encode($value) : $value;
                }, $record);
            }, $history);

            // Send the response as a JSON object
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'history' => $history
            ]);
            exit;
            
        } catch (\Exception $e) {
            // If an error occurs, log the error and stack trace, then return a 500 error with a JSON response
            error_log("Error in getHistory: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Clean any previous output to avoid header issues
            while (ob_get_level()) {
                ob_end_clean();
            }

            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => "Error loading history: " . $e->getMessage()
            ]);
            exit;
        }
    }

    public function getMachineInfo($request, $response) {
        try {
            // Get the machine ID from the request parameters
            $machineId = $request->getParam("id");
            if (!$machineId) {
                // If no machine ID is provided, throw an exception
                throw new \Exception("Machine ID not provided.");
            }

            // Log the machine ID being fetched for debugging purposes
            error_log("Fetching information for machine ID: " . $machineId);

            // Get the machine details using the machine model
            $machine = $this->machineModel->getMachineById($machineId);
            if (!$machine) {
                // If the machine is not found, throw an exception
                throw new \Exception("Machine not found.");
            }

            // Set the response header to indicate it's a JSON response
            $response->setHeader('Content-Type', 'application/json');
            
            // Return the machine data as a JSON response
            if ($machine) {
                $response->setBody(json_encode([
                    'success' => true,
                    'data' => $machine
                ]));
            } else {
                // If machine not found, set the status to 404 and return an error message
                $response->setStatus(404);
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Machine not found'
                ]));
            }
            
            return $response;
            
        } catch (\Exception $e) {
            // If an error occurs, log the error and return a 500 error with a JSON response
            error_log("Error in getMachineInfo: " . $e->getMessage());
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
