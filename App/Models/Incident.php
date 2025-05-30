<?php
namespace App\Models;

class Incident {
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el modelo de incidencias
     * 
     * @param \PDO $conn conexión a la base de datos
     */
    public function __construct(\PDO $conn) {
        $this->sql = $conn;
    }
    
    public function create($data) {
        try {
            // Validar datos requeridos
            if (empty($data['description']) || empty($data['machine_id'])) {
                throw new \Exception("Faltan campos requeridos");
            }

            // Debug: Imprimir datos recibidos
            error_log("Datos recibidos en create de Incident: " . print_r($data, true));

            // Validar que la máquina existe
            $stmt = $this->sql->prepare("SELECT id FROM Machine WHERE id = ?");
            $stmt->execute([(int)$data['machine_id']]);
            if (!$stmt->fetch()) {
                throw new \Exception("La máquina seleccionada no existe");
            }

            // Procesar el técnico asignado
            $technicianId = null;
            if (!empty($data['responsible_technician_id'])) {
                $technicianId = (int)$data['responsible_technician_id'];
                
                // Validar que el técnico existe y es un técnico
                $techStmt = $this->sql->prepare("SELECT id FROM User WHERE id = ? AND role = 'technician'");
                $techStmt->execute([$technicianId]);
                if (!$techStmt->fetch()) {
                    error_log("Técnico no válido o no es un técnico: " . $technicianId);
                    throw new \Exception("El técnico seleccionado no es válido");
                }
            }

            error_log("ID del técnico final: " . ($technicianId ?? 'null'));

            // Preparar la consulta
            $sql = "INSERT INTO Incident (description, priority, status, registered_date, machine_id, responsible_technician_id) 
                    VALUES (:description, :priority, :status, NOW(), :machine_id, :technician_id)";
            
            $stmt = $this->sql->prepare($sql);
            
            // Preparar los parámetros
            $params = [
                ':description' => $data['description'],
                ':priority' => $data['priority'] ?? 'medium',
                ':status' => 'pending',
                ':machine_id' => (int)$data['machine_id'],
                ':technician_id' => $technicianId
            ];

            error_log("Parámetros finales para la inserción: " . print_r($params, true));
            
            // Ejecutar la consulta
            if (!$stmt->execute($params)) {
                $error = $stmt->errorInfo();
                error_log("Error en la inserción de incidencia: " . implode(", ", $error));
                throw new \Exception("Error al crear la incidencia: " . $error[2]);
            }

            $newId = $this->sql->lastInsertId();
            error_log("Incidencia creada con ID: " . $newId);
            return $newId;

        } catch (\PDOException $e) {
            error_log("Error en la inserción de incidencia: " . $e->getMessage());
            throw new \Exception("Error al crear la incidencia: " . $e->getMessage());
        }
    }

    public function getAllTechnicians() {
        try {
            // Si el usuario es técnico, solo se muestra a sí mismo
            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'technician') {
                $stmt = $this->sql->prepare("
                    SELECT id, name, surname 
                    FROM User 
                    WHERE role = 'technician' 
                    AND id = ?
                ");
                $stmt->execute([$_SESSION["user"]["id"]]);
            } 
            // Si es administrador o supervisor, muestra todos los técnicos
            else {
                $stmt = $this->sql->prepare("
                    SELECT id, name, surname 
                    FROM User 
                    WHERE role = 'technician'
                ");
                $stmt->execute();
            }
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getAllTechnicians: " . $e->getMessage());
            throw new \Exception("Error al obtener los técnicos");
        }
    }

    public function getAllMachines() {
        try {
            // Si el usuario es técnico, mostrar máquinas disponibles
            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'technician') {
                $sql = "SELECT DISTINCT m.id, m.name 
                       FROM Machine m 
                       WHERE NOT EXISTS (
                           SELECT 1 
                           FROM Incident i 
                           WHERE i.machine_id = m.id 
                           AND i.status IN ('pending', 'in progress')
                       )
                       OR m.id NOT IN (
                           SELECT machine_id 
                           FROM Incident 
                           WHERE status IN ('pending', 'in progress')
                       )
                       ORDER BY m.name";
            } 
            // Si es administrador o supervisor, mostrar todas las máquinas
            else {
                $sql = "SELECT id, name FROM Machine ORDER BY name";
            }
            
            error_log("SQL para obtener máquinas: " . $sql);
            $stmt = $this->sql->prepare($sql);
            $stmt->execute();
            $machines = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Máquinas encontradas: " . count($machines));
            error_log("Máquinas: " . print_r($machines, true));
            
            return $machines;
        } catch (\PDOException $e) {
            error_log("Error en getAllMachines: " . $e->getMessage());
            throw new \Exception("Error al obtener las máquinas");
        }
    }

    public function getIncidentById($id) {
        $stmt = $this->sql->prepare("SELECT * FROM Incident WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllIncidents() {
        $stmt = $this->sql->prepare("SELECT i.*, m.name as machine_name, u.name as technician_name 
                                    FROM Incident i 
                                    LEFT JOIN Machine m ON i.machine_id = m.id 
                                    LEFT JOIN User u ON i.responsible_technician_id = u.id 
                                    ORDER BY i.registered_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el estado de una incidencia
     * 
     * @param int $incidentId ID de la incidencia
     * @param string $status Nuevo estado ('pending', 'in progress', 'resolved')
     * @return bool 
     * @throws \Exception
     */
    public function updateIncidentStatus($incidentId, $status) {
        try {
            error_log("=== INICIO updateIncidentStatus en Modelo ===");
            error_log("Parámetros recibidos:");
            error_log("- ID: " . $incidentId);
            error_log("- Estado solicitado: " . $status);
            
            // Validar el estado
            $validStatuses = ['pending', 'in progress', 'resolved'];
            error_log("Estados válidos: " . implode(", ", $validStatuses));
            
            if (!in_array($status, $validStatuses)) {
                error_log("Estado no válido: " . $status);
                // Intentar convertir el formato si es necesario
                $status = str_replace('_', ' ', $status);
                if (!in_array($status, $validStatuses)) {
                    throw new \Exception("Estado no válido");
                }
            }

            // Primero, verificar el estado actual
            $stmt = $this->sql->prepare("SELECT id, status FROM Incident WHERE id = ?");
            $stmt->execute([$incidentId]);
            $incident = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$incident) {
                error_log("La incidencia no existe: " . $incidentId);
                throw new \Exception("La incidencia no existe");
            }
            
            error_log("Estado actual en la base de datos: " . ($incident['status'] ?? 'NULL'));

            // Actualizar el estado
            $updateSql = "UPDATE Incident SET status = ? WHERE id = ?";
            error_log("SQL de actualización: " . $updateSql);
            error_log("Parámetros de actualización: [" . $status . ", " . $incidentId . "]");
            
            $stmt = $this->sql->prepare($updateSql);
            $success = $stmt->execute([$status, $incidentId]);
            
            if (!$success) {
                $error = $stmt->errorInfo();
                error_log("Error en la actualización: " . implode(", ", $error));
                throw new \Exception("Error al actualizar el estado: " . implode(", ", $error));
            }

            // Verificar que el cambio se realizó correctamente
            $stmt = $this->sql->prepare("SELECT id, status FROM Incident WHERE id = ?");
            $stmt->execute([$incidentId]);
            $updatedIncident = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            error_log("Estado después de la actualización: " . ($updatedIncident['status'] ?? 'NULL'));
            
            if ($updatedIncident['status'] !== $status) {
                error_log("¡ADVERTENCIA! El estado no coincide después de la actualización");
                error_log("Estado esperado: " . $status);
                error_log("Estado actual: " . $updatedIncident['status']);
                throw new \Exception("El estado no se actualizó correctamente");
            }

            error_log("=== FIN updateIncidentStatus en Modelo ===");
            return true;
            
        } catch (\PDOException $e) {
            error_log("Error PDO en updateIncidentStatus: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Error de base de datos al actualizar el estado: " . $e->getMessage());
        }
    }

    public function getIncidentsByMachine($machineId) {
        try {
            // Función auxiliar para limpiar strings
            $cleanString = function($str) {
                if (!is_string($str)) return $str;
                
                // Convertir a UTF-8 si no lo está
                if (!mb_check_encoding($str, 'UTF-8')) {
                    $str = mb_convert_encoding($str, 'UTF-8', 'auto');
                }
                
                // Eliminar caracteres no imprimibles y normalizar espacios
                $str = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $str);
                $str = preg_replace('/\s+/u', ' ', $str);
                return trim($str);
            };

            // Obtener información de la máquina
            $machineStmt = $this->sql->prepare("
                SELECT 
                    id,
                    name,
                    model,
                    manufacturer,
                    location
                FROM Machine
                WHERE id = ?
            ");
            
            $machineStmt->execute([$machineId]);
            $machineInfo = $machineStmt->fetch(\PDO::FETCH_ASSOC);

            if (!$machineInfo) {
                return [
                    'success' => false,
                    'message' => "Máquina no encontrada"
                ];
            }

            // Limpiar datos de la máquina
            foreach ($machineInfo as $key => $value) {
                $machineInfo[$key] = $cleanString($value);
            }

            // Obtener incidencias
            $stmt = $this->sql->prepare("
                SELECT 
                    i.id,
                    i.description,
                    i.priority,
                    CASE 
                        WHEN i.status = 'in_progress' THEN 'in progress'
                        WHEN i.status IS NULL THEN 'pending'
                        ELSE i.status 
                    END as status,
                    DATE_FORMAT(i.registered_date, '%Y-%m-%d %H:%i:%s') as registered_date,
                    COALESCE(CONCAT(TRIM(u.name), ' ', TRIM(u.surname)), '') as technician_name
                FROM Incident i
                LEFT JOIN User u ON i.responsible_technician_id = u.id
                WHERE i.machine_id = ?
                ORDER BY i.registered_date DESC
            ");

            $stmt->execute([$machineId]);
            $incidents = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Limpiar y validar datos de incidencias
            foreach ($incidents as &$incident) {
                // Limpiar strings
                foreach ($incident as $key => $value) {
                    $incident[$key] = $cleanString($value);
                }
                
                // Validar nombre del técnico
                $incident['technician_name'] = $incident['technician_name'] ? 
                    $cleanString($incident['technician_name']) : 'No asignado';
                
                // Validar estado
                $incident['status'] = in_array($incident['status'], ['pending', 'in progress', 'resolved']) ? 
                    $incident['status'] : 'pending';

                // Si el estado es 'in_progress', convertirlo a 'in progress'
                if ($incident['status'] === 'in_progress') {
                    $incident['status'] = 'in progress';
                }
            }

            // Calcular estadísticas
            $stats = [
                'total_incidents' => count($incidents),
                'pending_incidents' => 0,
                'in_progress_incidents' => 0,
                'resolved_incidents' => 0
            ];

            foreach ($incidents as $incident) {
                switch ($incident['status']) {
                    case 'pending': 
                        $stats['pending_incidents']++; 
                        break;
                    case 'in progress': 
                        $stats['in_progress_incidents']++; 
                        break;
                    case 'resolved': 
                        $stats['resolved_incidents']++; 
                        break;
                }
            }

            // Traducir estados a español
            foreach ($incidents as &$incident) {
                $incident['status_text'] = [
                    'pending' => 'Pendiente',
                    'in progress' => 'En Proceso',
                    'resolved' => 'Resuelto'
                ][$incident['status']] ?? $incident['status'];
            }

            // Preparar respuesta
            $result = [
                'success' => true,
                'machine' => array_merge($machineInfo, $stats),
                'data' => array_values($incidents) // Asegurar que es un array indexado
            ];

            // Verificar que se puede codificar como JSON
            $json = json_encode($result, JSON_PARTIAL_OUTPUT_ON_ERROR);
            if ($json === false) {
                throw new \Exception("Error al codificar datos: " . json_last_error_msg());
            }

            return json_decode($json, true); // Decodificar de nuevo para asegurar datos limpios

        } catch (\Exception $e) {
            error_log("Error en getIncidentsByMachine: " . $e->getMessage());
            return [
                'success' => false,
                'message' => "Error al obtener las incidencias: " . $e->getMessage()
            ];
        }
    }
}