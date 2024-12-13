<?php
namespace App\Controllers;

class AdminController {
    public function __construct() {
        // Constructor
    }

    public function index($request, $response, $container) {
        // Verificar que el usuario sea administrador
        if (!isset($_SESSION["user"]["role"]) || $_SESSION["user"]["role"] !== 'administrator') {
            header('Location: /index');
            exit;
        }

        // Obtener estadísticas para el panel de administración
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $sql = $db->getConnection();

        try {
            // Total de usuarios
            $stmt = $sql->query("SELECT COUNT(*) as total FROM User");
            $totalUsers = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total de máquinas
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Machine");
            $totalMachines = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Total de incidencias
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident");
            $totalIncidents = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Incidencias pendientes
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Incident WHERE status = 'pending'");
            $pendingIncidents = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Mantenimientos programados
            $stmt = $sql->query("SELECT COUNT(*) as total FROM Maintenance WHERE status = 'pending'");
            $pendingMaintenance = $stmt->fetch(\PDO::FETCH_ASSOC)['total'];

            // Pasar datos a la vista
            $response->set("stats", [
                'total_users' => $totalUsers,
                'total_machines' => $totalMachines,
                'total_incidents' => $totalIncidents,
                'pending_incidents' => $pendingIncidents,
                'pending_maintenance' => $pendingMaintenance
            ]);

            // Establecer la plantilla
            $response->setTemplate("adminPanel.php");

        } catch (\PDOException $e) {
            // Log del error
            error_log("Error en AdminController: " . $e->getMessage());
            
            // Mostrar mensaje de error en la vista
            $response->set("error_message", "Error al cargar las estadísticas del panel de administración");
            $response->setTemplate("adminPanel.php");
        }

        return $response;
    }
} 