<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use \Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use \Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class ctrladminPanel
{
    // Function to handle the admin panel, providing statistics and recent activity
    function adminPanel(Request $request, Response $response, Container $container)
    {
        // Initializing the statistics array with default values
        $stats = [
            'system_status' => [
                'database' => true,
                'last_backup' => date('Y-m-d H:i:s'),
                'disk_usage' => '45%',
                'server_load' => '30%'
            ],
            'total_users' => 0, // Total users count
            'total_machines' => 0, // Total machines count
            'total_incidents' => 0, // Total incidents count
            'pending_incidents' => 0, // Total pending incidents
            'pending_maintenance' => 0, // Total pending maintenance tasks
            'users_by_role' => [], // Users grouped by role
            'incidents_by_status' => [], // Incidents grouped by status
            'recent_activity' => [], // Recent activity (last 10 actions)
            'machines_with_most_incidents' => [] // Machines with the most incidents
        ];

        try {
            // Creating a new database connection using the provided credentials
            $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
            $sql = $db->getConnection(); // Getting the database connection object

            // Query to get the total number of users
            $stmt = $sql->query("SELECT COUNT(*) as total FROM User");
            $stats['total_users'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Query to get the total number of machines
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Machine");
            $stats['total_machines'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Query to get the total number of incidents
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident");
            $stats['total_incidents'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Query to get the total number of pending incidents
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident WHERE status = 'pending'");
            $stats['pending_incidents'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Query to get the total number of pending maintenance tasks
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Maintenance WHERE status = 'pending'");
            $stats['pending_maintenance'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Query to get users grouped by role
            $stmt = $sql->query("SELECT role, COUNT(*) as count FROM User GROUP BY role");
            $stats['users_by_role'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Query to get incidents grouped by status
            $stmt = $sql->query("SELECT status, COUNT(*) as count FROM Incident GROUP BY status");
            $stats['incidents_by_status'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Query to get the 10 most recent activities from incidents and maintenance
            $stmt = $sql->query("
                (SELECT 
                    'incident' as type, 
                    registered_date as date, 
                    description as detail, 
                    status
                FROM Incident 
                ORDER BY registered_date DESC 
                LIMIT 5)
                UNION ALL
                (SELECT 
                    'maintenance' as type, 
                    scheduled_date as date, 
                    description as detail, 
                    status
                FROM Maintenance 
                ORDER BY scheduled_date DESC 
                LIMIT 5)
                ORDER BY date DESC 
                LIMIT 10
            ");
            $stats['recent_activity'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Query to get the machines with the most incidents
            $stmt = $sql->query("
                SELECT m.name, COUNT(i.id) as incident_count
                FROM Machine m
                LEFT JOIN Incident i ON m.id = i.machine_id
                GROUP BY m.id, m.name
                ORDER BY incident_count DESC
                LIMIT 5
            ");
            $stats['machines_with_most_incidents'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        } catch (\PDOException $e) {
            // Error handling in case of a database query failure
            error_log("Error en adminPanel: " . $e->getMessage()); // Log the error message
            // Set error message to be displayed in the response
            $response->set("error_message", "Error al cargar las estadísticas del panel de administración: " . $e->getMessage());
        }

        // Pass the gathered statistics data to the view
        $response->set("stats", $stats);
        // Set the template to be used for rendering the response
        $response->setTemplate('adminPanel.php');
        
        // Return the response object with the data
        return $response;
    }
}
