<?php
namespace App\Controllers;

class MachinesController {
    private $db;

    public function __construct($container) {
        $this->db = $container->get("db");
    }

    public function showAssignedTechnicians($request, $response, $container) {
        try {
            // Obtener incidencias asignadas
            $incidents = $this->db->prepare("
                SELECT 
                    i.id,
                    CONCAT(u.name, ' ', u.surname) as technician_name,
                    'incident' as type,
                    i.id as task_id,
                    i.description,
                    i.registered_date as assigned_date,
                    i.responsible_technician_id as technician_id
                FROM Incident i
                LEFT JOIN User u ON i.responsible_technician_id = u.id
                WHERE i.status != 'resolved'
                ORDER BY i.registered_date DESC
            ");
            $incidents->execute();
            $incidents = $incidents->fetchAll(\PDO::FETCH_ASSOC);

            // Obtener mantenimientos asignados
            $maintenance = $this->db->prepare("
                SELECT 
                    m.id,
                    u.name as technician_name,
                    'maintenance' as type,
                    m.id as task_id,
                    m.description,
                    m.scheduled_date as assigned_date,
                    mt.technician_id
                FROM Maintenance m
                LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                LEFT JOIN User u ON mt.technician_id = u.id
                WHERE m.status != 'completed'
                ORDER BY m.scheduled_date DESC
            ");
            $maintenance->execute();
            $maintenance = $maintenance->fetchAll(\PDO::FETCH_ASSOC);

            // Combinar resultados
            $assignments = array_merge($incidents, $maintenance);

            // Ordenar por fecha
            usort($assignments, function($a, $b) {
                return strtotime($b['assigned_date']) - strtotime($a['assigned_date']);
            });

            // Obtener todos los técnicos disponibles
            $technicians = $this->db->prepare("
                SELECT id, CONCAT(name, ' ', surname) as name 
                FROM User 
                WHERE role = 'technician'
                ORDER BY name ASC
            ");
            $technicians->execute();
            $technicians = $technicians->fetchAll(\PDO::FETCH_ASSOC);

            // Debug
            error_log("Assignments después de la consulta: " . print_r($assignments, true));

            $response->set("assignments", $assignments);
            $response->set("technicians", $technicians);
            $response->SetTemplate("assigned_technicians.php");

            return $response;
        } catch (\Exception $e) {
            error_log("Error en MachinesController::showAssignedTechnicians: " . $e->getMessage());
            $response->set("error", "Error al cargar la página: " . $e->getMessage());
            $response->SetTemplate("assigned_technicians.php");
            return $response;
        }
    }

    public function changeTechnician($request, $response) {
        try {
            // Obtener los datos del request
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['assignmentId']) || !isset($data['newTechnicianId'])) {
                throw new \Exception('Faltan datos requeridos');
            }

            error_log("Datos recibidos: " . print_r($data, true));

            $this->db->beginTransaction();

            try {
                // Primero verificamos si es una incidencia
                $stmt = $this->db->prepare("SELECT id FROM Incident WHERE id = ?");
                $stmt->execute([$data['assignmentId']]);
                $isIncident = $stmt->fetch();

                if ($isIncident) {
                    error_log("Actualizando incidencia...");
                    $stmt = $this->db->prepare("
                        UPDATE Incident 
                        SET responsible_technician_id = ? 
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([$data['newTechnicianId'], $data['assignmentId']]);
                    error_log("Resultado de la actualización de incidencia: " . ($result ? "éxito" : "fallo"));
                } else {
                    error_log("Actualizando mantenimiento...");
                    // Primero eliminamos la asignación anterior
                    $stmt = $this->db->prepare("
                        DELETE FROM MaintenanceTechnician 
                        WHERE maintenance_id = ?
                    ");
                    $result = $stmt->execute([$data['assignmentId']]);
                    error_log("Resultado de la eliminación: " . ($result ? "éxito" : "fallo"));

                    // Luego insertamos la nueva asignación
                    $stmt = $this->db->prepare("
                        INSERT INTO MaintenanceTechnician (maintenance_id, technician_id)
                        VALUES (?, ?)
                    ");
                    $result = $stmt->execute([$data['assignmentId'], $data['newTechnicianId']]);
                    error_log("Resultado de la inserción: " . ($result ? "éxito" : "fallo"));
                }

                $this->db->commit();
                error_log("Transacción completada con éxito");
                
                // Obtener el nombre del nuevo técnico para la respuesta
                $stmt = $this->db->prepare("SELECT CONCAT(name, ' ', surname) as name FROM User WHERE id = ?");
                $stmt->execute([$data['newTechnicianId']]);
                $technicianName = $stmt->fetchColumn();
                
                // Establecer el header Content-Type antes de enviar la respuesta
                header('Content-Type: application/json');
                
                // Enviar la respuesta JSON directamente
                echo json_encode([
                    'success' => true,
                    'message' => 'Técnico actualizado correctamente',
                    'technicianName' => $technicianName
                ]);
                exit;
                
            } catch (\Exception $e) {
                $this->db->rollBack();
                error_log("Error en la transacción: " . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            error_log("Error en MachinesController::changeTechnician: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
} 