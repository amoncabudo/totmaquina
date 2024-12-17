<?php
namespace App\Controllers;

class MachinesController {
    private $db;

    public function __construct($container) {
        $this->db = $container->get("db");
    }

    public function showAssignedTechnicians($request, $response, $container) {
        try {
            error_log("=== INICIO showAssignedTechnicians ===");
            
            // Obtener incidencias asignadas
            $incidents = $this->db->prepare("
                SELECT 
                    i.id,
                    CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name,
                    'incident' as type,
                    i.id as task_id,
                    i.description,
                    i.registered_date as assigned_date,
                    i.responsible_technician_id as technician_id,
                    u.id as current_technician_id
                FROM Incident i
                LEFT JOIN User u ON i.responsible_technician_id = u.id
                WHERE i.status != 'resolved'
                ORDER BY i.registered_date DESC
            ");
            $incidents->execute();
            $incidents = $incidents->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Incidencias encontradas: " . count($incidents));
            error_log("Datos de incidencias: " . print_r($incidents, true));

            // Obtener mantenimientos asignados
            $maintenance = $this->db->prepare("
                SELECT 
                    m.id,
                    CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name,
                    'maintenance' as type,
                    m.id as task_id,
                    m.description,
                    m.scheduled_date as assigned_date,
                    mt.technician_id,
                    u.id as current_technician_id
                FROM Maintenance m
                LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                LEFT JOIN User u ON mt.technician_id = u.id
                WHERE m.status != 'completed'
                ORDER BY m.scheduled_date DESC
            ");
            $maintenance->execute();
            $maintenance = $maintenance->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Mantenimientos encontrados: " . count($maintenance));
            error_log("Datos de mantenimientos: " . print_r($maintenance, true));

            // Combinar resultados
            $assignments = array_merge($incidents, $maintenance);

            // Ordenar por fecha
            usort($assignments, function($a, $b) {
                return strtotime($b['assigned_date']) - strtotime($a['assigned_date']);
            });

            // Obtener todos los técnicos
            $technicians = $this->db->prepare("
                SELECT 
                    id, 
                    CONCAT(name, ' ', COALESCE(surname, '')) as name 
                FROM User 
                WHERE role = 'technician'
                ORDER BY name ASC
            ");
            $technicians->execute();
            $technicians = $technicians->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Técnicos disponibles: " . count($technicians));
            error_log("Lista de técnicos: " . print_r($technicians, true));

            $response->set("assignments", $assignments);
            $response->set("technicians", $technicians);
            $response->SetTemplate("assigned_technicians.php");

            error_log("=== FIN showAssignedTechnicians ===");
            return $response;
        } catch (\Exception $e) {
            error_log("Error en MachinesController::showAssignedTechnicians: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $response->set("error", "Error al cargar la página: " . $e->getMessage());
            $response->SetTemplate("assigned_technicians.php");
            return $response;
        }
    }

    public function changeTechnician($request, $response, $container) {
        try {
            $inputData = file_get_contents('php://input');
            error_log("=== INICIO changeTechnician ===");
            error_log("Datos recibidos (raw): " . $inputData);
            
            $data = json_decode($inputData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error al decodificar JSON: " . json_last_error_msg());
            }
            
            error_log("Datos decodificados: " . print_r($data, true));
            
            // Validar datos recibidos
            if (!isset($data['assignmentId']) || !isset($data['newTechnicianId']) || !isset($data['type'])) {
                throw new \Exception("Faltan datos necesarios para el cambio de técnico");
            }

            // Validar que el técnico existe
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM User 
                WHERE id = ? AND role = 'technician'
            ");
            $stmt->execute([$data['newTechnicianId']]);
            if ($stmt->fetchColumn() == 0) {
                throw new \Exception("El técnico seleccionado no existe");
            }

            $this->db->beginTransaction();
            error_log("Iniciando transacción");

            try {
                if ($data['type'] === 'incident') {
                    error_log("Actualizando técnico para incidencia ID: " . $data['assignmentId']);
                    
                    // Actualizar el técnico responsable en la tabla Incident
                    $stmt = $this->db->prepare("
                        UPDATE Incident 
                        SET responsible_technician_id = ?
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([$data['newTechnicianId'], $data['assignmentId']]);
                    
                    if (!$result) {
                        throw new \Exception("Error al actualizar el técnico en la incidencia");
                    }
                    
                    error_log("Filas actualizadas en Incident: " . $stmt->rowCount());
                    
                } else if ($data['type'] === 'maintenance') {
                    error_log("Actualizando técnico para mantenimiento ID: " . $data['assignmentId']);
                    
                    // Primero eliminamos la asignación actual
                    $stmt = $this->db->prepare("
                        DELETE FROM MaintenanceTechnician 
                        WHERE maintenance_id = ?
                    ");
                    $result = $stmt->execute([$data['assignmentId']]);
                    error_log("Filas eliminadas en MaintenanceTechnician: " . $stmt->rowCount());

                    // Luego insertamos la nueva asignación
                    $stmt = $this->db->prepare("
                        INSERT INTO MaintenanceTechnician (maintenance_id, technician_id)
                        VALUES (?, ?)
                    ");
                    $result = $stmt->execute([$data['assignmentId'], $data['newTechnicianId']]);
                    
                    if (!$result) {
                        throw new \Exception("Error al insertar el nuevo técnico en mantenimiento");
                    }
                    
                    error_log("Nueva asignación insertada en MaintenanceTechnician");
                }

                $this->db->commit();
                error_log("Transacción completada con éxito");
                
                header('Content-Type: application/json');
                
                $response = [
                    'success' => true,
                    'message' => 'Técnico actualizado correctamente',
                    'data' => [
                        'assignmentId' => $data['assignmentId'],
                        'newTechnicianId' => $data['newTechnicianId'],
                        'type' => $data['type']
                    ]
                ];
                
                error_log("Respuesta a enviar: " . print_r($response, true));
                error_log("=== FIN changeTechnician ===");
                
                echo json_encode($response);
                exit;
                
            } catch (\Exception $e) {
                $this->db->rollBack();
                error_log("Error durante la transacción: " . $e->getMessage());
                throw $e;
            }
            
        } catch (\Exception $e) {
            error_log("Error en changeTechnician: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
} 