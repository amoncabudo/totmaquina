<?php
namespace App\Controllers;

class MachinesController {
    private $db;

    // Constructor que recibe el contenedor de dependencias
    public function __construct($container) {
        $this->db = $container->get("db");
    }

    // Función que muestra las incidencias y mantenimientos asignados
    public function showAssignedTechnicians($request, $response, $container) {
        try {
            // Obtener incidencias no resueltas
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

            // Obtener mantenimientos no completados
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

            // Combinar los resultados de incidencias y mantenimientos
            $assignments = array_merge($incidents, $maintenance);

            // Ordenar las asignaciones por fecha (más reciente primero)
            usort($assignments, function($a, $b) {
                return strtotime($b['assigned_date']) - strtotime($a['assigned_date']);
            });

            // Obtener la lista de técnicos disponibles
            $technicians = $this->db->prepare("
                SELECT id, CONCAT(name, ' ', surname) as name 
                FROM User 
                WHERE role = 'technician'
                ORDER BY name ASC
            ");
            $technicians->execute();
            $technicians = $technicians->fetchAll(\PDO::FETCH_ASSOC);

            // Debug: Log para verificar el contenido de las asignaciones
            error_log("Assignments después de la consulta: " . print_r($assignments, true));

            // Pasar los datos a la respuesta para el template
            $response->set("assignments", $assignments);
            $response->set("technicians", $technicians);

            // Establecer el template para mostrar las asignaciones
            $response->SetTemplate("assigned_technicians.php");

            return $response;
        } catch (\Exception $e) {
            // En caso de error, registrar el error y devolver la respuesta con un mensaje
            error_log("Error en MachinesController::showAssignedTechnicians: " . $e->getMessage());
            $response->set("error", "Error al cargar la página: " . $e->getMessage());
            $response->SetTemplate("assigned_technicians.php");
            return $response;
        }
    }

    // Función para cambiar el técnico asignado a una incidencia o mantenimiento
    public function changeTechnician($request, $response) {
        try {
            // Obtener los datos del cuerpo del request
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Verificar que se proporcionaron los datos necesarios
            if (!isset($data['assignmentId']) || !isset($data['newTechnicianId'])) {
                throw new \Exception('Faltan datos requeridos');
            }

            // Log de los datos recibidos
            error_log("Datos recibidos: " . print_r($data, true));

            // Iniciar una transacción para asegurar la integridad de la base de datos
            $this->db->beginTransaction();

            try {
                // Verificar si la asignación corresponde a una incidencia
                $stmt = $this->db->prepare("SELECT id FROM Incident WHERE id = ?");
                $stmt->execute([$data['assignmentId']]);
                $isIncident = $stmt->fetch();

                if ($isIncident) {
                    // Actualizar la incidencia con el nuevo técnico
                    error_log("Actualizando incidencia...");
                    $stmt = $this->db->prepare("
                        UPDATE Incident 
                        SET responsible_technician_id = ? 
                        WHERE id = ?
                    ");
                    $result = $stmt->execute([$data['newTechnicianId'], $data['assignmentId']]);
                    error_log("Resultado de la actualización de incidencia: " . ($result ? "éxito" : "fallo"));
                } else {
                    // Actualizar un mantenimiento
                    error_log("Actualizando mantenimiento...");
                    // Eliminar la asignación anterior
                    $stmt = $this->db->prepare("
                        DELETE FROM MaintenanceTechnician 
                        WHERE maintenance_id = ?
                    ");
                    $result = $stmt->execute([$data['assignmentId']]);
                    error_log("Resultado de la eliminación: " . ($result ? "éxito" : "fallo"));

                    // Insertar la nueva asignación
                    $stmt = $this->db->prepare("
                        INSERT INTO MaintenanceTechnician (maintenance_id, technician_id)
                        VALUES (?, ?)
                    ");
                    $result = $stmt->execute([$data['assignmentId'], $data['newTechnicianId']]);
                    error_log("Resultado de la inserción: " . ($result ? "éxito" : "fallo"));
                }

                // Confirmar los cambios en la base de datos
                $this->db->commit();
                error_log("Transacción completada con éxito");
                
                // Obtener el nombre del nuevo técnico para la respuesta
                $stmt = $this->db->prepare("SELECT CONCAT(name, ' ', surname) as name FROM User WHERE id = ?");
                $stmt->execute([$data['newTechnicianId']]);
                $technicianName = $stmt->fetchColumn();
                
                // Establecer el encabezado Content-Type para la respuesta JSON
                header('Content-Type: application/json');
                
                // Enviar la respuesta en formato JSON
                echo json_encode([
                    'success' => true,
                    'message' => 'Técnico actualizado correctamente',
                    'technicianName' => $technicianName
                ]);
                exit;
                
            } catch (\Exception $e) {
                // En caso de error, revertir la transacción
                $this->db->rollBack();
                error_log("Error en la transacción: " . $e->getMessage());
                throw $e;
            }
        } catch (\Exception $e) {
            // Manejo de errores en la función de cambio de técnico
            error_log("Error en MachinesController::changeTechnician: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Enviar la respuesta de error en formato JSON
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }
}
