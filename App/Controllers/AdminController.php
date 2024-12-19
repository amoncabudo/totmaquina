<?php
namespace App\Controllers;

class AdminController {
    public function __construct() {
        // Constructor
    }

    public function index($request, $response, $container) {
        // Verify that the user is an administrator
        if (!isset($_SESSION["user"]["role"]) || $_SESSION["user"]["role"] !== 'administrator') {
            header('Location: /index');
            exit;
        }

        // Retrieve statistics for the admin panel
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $sql = $db->getConnection();

        try {
            // Total number of users
            $stmt = $sql->query("SELECT COUNT(*) as total FROM User");
            $totalUsers = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total number of machines
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Machine");
            $totalMachines = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total number of incidents
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident");
            $totalIncidents = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Pending incidents
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident WHERE status = 'pending'");
            $pendingIncidents = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Scheduled maintenance
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Maintenance WHERE status = 'pending'");
            $pendingMaintenance = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Pass data to the view
            $response->set("stats", [
                'total_users' => $totalUsers,
                'total_machines' => $totalMachines,
                'total_incidents' => $totalIncidents,
                'pending_incidents' => $pendingIncidents,
                'pending_maintenance' => $pendingMaintenance
            ]);

            // Set the template
            $response->setTemplate("adminPanel.php");

        } catch (\PDOException $e) {
            // Log the error
            error_log("Error in AdminController: " . $e->getMessage());
            
            // Display an error message in the view
            $response->set("error_message", "Error loading admin panel statistics");
            $response->setTemplate("adminPanel.php");
        }

        return $response;
    }
}
