<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrladminPanel
{
    function adminPanel(Request $request, Response $response, Container $container)
    {
        // Inicializar array de estadísticas con valores por defecto
        $stats = [
            'system_status' => [
                'database' => true,
                'last_backup' => date('Y-m-d H:i:s'),
                'disk_usage' => '45%',
                'server_load' => '30%'
            ],
            'total_users' => 0,
            'total_machines' => 0,
            'total_incidents' => 0,
            'pending_incidents' => 0,
            'pending_maintenance' => 0,
            'users_by_role' => [],
            'incidents_by_status' => [],
            'recent_activity' => [],
            'machines_with_most_incidents' => []
        ];

        try {
            // Obtener estadísticas para el panel de administración
            $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
            $sql = $db->getConnection();

            // Total de usuarios
            $stmt = $sql->query("SELECT COUNT(*) as total FROM User");
            $stats['total_users'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total de máquinas
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Machine");
            $stats['total_machines'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total de incidencias
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident");
            $stats['total_incidents'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Incidencias pendientes
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident WHERE status = 'pending'");
            $stats['pending_incidents'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Mantenimientos programados
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Maintenance WHERE status = 'pending'");
            $stats['pending_maintenance'] = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Usuarios por rol
            $stmt = $sql->query("SELECT role, COUNT(*) as count FROM User GROUP BY role");
            $stats['users_by_role'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Incidencias por estado
            $stmt = $sql->query("SELECT status, COUNT(*) as count FROM Incident GROUP BY status");
            $stats['incidents_by_status'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Actividad Reciente (últimas 10 acciones)
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

            // Máquinas con más incidencias
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
            error_log("Error en adminPanel: " . $e->getMessage());
            $response->set("error_message", "Error al cargar las estadísticas del panel de administración: " . $e->getMessage());
        }

        // Pasar los datos a la vista
        $response->set("stats", $stats);
        $response->setTemplate('adminPanel.php');
        return $response;
    }
}
