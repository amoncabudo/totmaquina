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
                AND i.responsible_technician_id IS NOT NULL
                ORDER BY i.registered_date DESC
            ");
            $incidents->execute();
            $incidents = $incidents->fetchAll(\PDO::FETCH_ASSOC);

            // Obtener mantenimientos asignados
            $maintenance = $this->db->prepare("
                SELECT 
                    m.id,
                    CONCAT(u.name, ' ', u.surname) as technician_name,
                    'maintenance' as type,
                    m.id as task_id,
                    m.description,
                    m.scheduled_date as assigned_date,
                    mt.technician_id
                FROM Maintenance m
                JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
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

            // Obtener todos los técnicos
            $technicians = $this->db->prepare("
                SELECT id, CONCAT(name, ' ', surname) as name 
                FROM User 
                WHERE role = 'technician'
            ");
            $technicians->execute();
            $technicians = $technicians->fetchAll(\PDO::FETCH_ASSOC);

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

    public function changeTechnician($request, $response, $container) {
        try {
            $inputData = file_get_contents('php://input');
            error_log("Datos recibidos (raw): " . $inputData);
            
            $data = json_decode($inputData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error al decodificar JSON: " . json_last_error_msg());
            }
            
            error_log("Datos decodificados: " . print_r($data, true));
            
            // Validar datos recibidos
            if (!isset($data['assignmentId']) || !isset($data['newTechnicianId'])) {
                throw new \Exception("Faltan datos necesarios para el cambio de técnico");
            }

            // Validar que el técnico existe
            $checkTechnician = $this->db->prepare("
                SELECT COUNT(*) FROM User 
                WHERE id = ? AND role = 'technician'
            ");
            $checkTechnician->execute([$data['newTechnicianId']]);
            if ($checkTechnician->fetchColumn() == 0) {
                throw new \Exception("El técnico seleccionado no existe");
            }

            // Determinar si es incidencia o mantenimiento
            $checkType = $this->db->prepare("
                SELECT 
                    CASE 
                        WHEN EXISTS (SELECT 1 FROM Incident WHERE id = ?) THEN 'incident'
                        WHEN EXISTS (SELECT 1 FROM Maintenance WHERE id = ?) THEN 'maintenance'
                        ELSE NULL
                    END as type
            ");
            $checkType->execute([$data['assignmentId'], $data['assignmentId']]);
            $type = $checkType->fetchColumn();
            error_log("Tipo de asignación encontrado: " . $type);

            if (!$type) {
                throw new \Exception("No se encontró la tarea especificada");
            }

            $this->db->beginTransaction();

            try {
                if ($type === 'incident') {
                    error_log("Actualizando incidencia...");
                    $stmt = $this->db->prepare("
                        UPDATE Incident 
                        SET responsible_technician_id = ?
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([$data['newTechnicianId'], $data['assignmentId']]);
                    error_log("Resultado de la actualización de incidencia: " . ($result ? "éxito" : "fallo"));
                    error_log("Filas afectadas en incidencia: " . $stmt->rowCount());
                } else {
                    error_log("Actualizando mantenimiento...");
                    // Primero eliminamos la asignación anterior
                    $stmt = $this->db->prepare("
                        DELETE FROM MaintenanceTechnician 
                        WHERE maintenance_id = ?
                    ");
                    $result = $stmt->execute([$data['assignmentId']]);
                    error_log("Resultado de la eliminación: " . ($result ? "éxito" : "fallo"));
                    error_log("Filas eliminadas en MaintenanceTechnician: " . $stmt->rowCount());

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
                
                // Establecer el header Content-Type antes de enviar la respuesta
                header('Content-Type: application/json');
                
                // Enviar la respuesta JSON directamente
                echo json_encode([
                    'success' => true,
                    'message' => 'Técnico actualizado correctamente'
                ]);
                exit; // Terminar la ejecución para evitar que el middleware modifique la respuesta
                
            } catch (\Exception $e) {
                $this->db->rollBack();
                error_log("Error en la transacción: " . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            error_log("Error en MachinesController::changeTechnician: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Establecer el header Content-Type antes de enviar la respuesta
            header('Content-Type: application/json');
            
            // Enviar la respuesta JSON directamente
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit; // Terminar la ejecución para evitar que el middleware modifique la respuesta
        }
    }
} 