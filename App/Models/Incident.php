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
            error_log("=== DEBUG: Creando nueva incidencia ===");
            error_log("Datos recibidos: " . print_r($data, true));

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

            error_log("ID del técnico validado: " . ($technicianId ?? 'null'));

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

            error_log("SQL: " . $sql);
            error_log("Parámetros: " . print_r($params, true));
            
            // Ejecutar la consulta
            if (!$stmt->execute($params)) {
                $error = $stmt->errorInfo();
                error_log("Error en la inserción: " . implode(", ", $error));
                throw new \Exception("Error al crear la incidencia: " . $error[2]);
            }

            $newId = $this->sql->lastInsertId();
            error_log("Incidencia creada exitosamente con ID: " . $newId);

            // Verificar que la incidencia se creó correctamente
            $verifyStmt = $this->sql->prepare("SELECT * FROM Incident WHERE id = ?");
            $verifyStmt->execute([$newId]);
            $newIncident = $verifyStmt->fetch(\PDO::FETCH_ASSOC);
            error_log("Datos de la nueva incidencia: " . print_r($newIncident, true));

            return $newId;

        } catch (\PDOException $e) {
            error_log("Error PDO en create: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
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
            // Si el usuario es técnico, solo mostrar máquinas sin técnico asignado
            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'technician') {-
                $sql = "SELECT m.id, m.name 
                       FROM Machine m 
                       LEFT JOIN Incident i ON m.id = i.machine_id AND i.status != 'resolved'
                       WHERE i.id IS NULL 
                       OR NOT EXISTS (
                           SELECT 1 
                           FROM Incident i2 
                           WHERE i2.machine_id = m.id 
                           AND i2.status != 'resolved'
                       )
                       GROUP BY m.id, m.name";
            } 
            // Si es administrador o supervisor, mostrar todas las máquinas
            else {
                $sql = "SELECT id, name FROM Machine";
            }
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
        try {
            error_log("=== DEBUG: Obteniendo todas las incidencias ===");
            
            $sql = "SELECT i.*, m.name as machine_name, 
                           CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name 
                    FROM Incident i 
                    LEFT JOIN Machine m ON i.machine_id = m.id 
                    LEFT JOIN User u ON i.responsible_technician_id = u.id 
                    ORDER BY i.registered_date DESC";
            
            error_log("SQL: " . $sql);
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute();
            
            $incidents = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Incidencias encontradas: " . count($incidents));
            error_log("Datos de incidencias: " . print_r($incidents, true));
            
            return $incidents;
        } catch (\PDOException $e) {
            error_log("Error en getAllIncidents: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            throw new \Exception("Error al obtener las incidencias: " . $e->getMessage());
        }
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
            // Validar el estado
            $validStatuses = ['pending', 'in progress', 'resolved'];
            if (!in_array($status, $validStatuses)) {
                throw new \Exception("Estado no válido");
            }

            // Verificar que la incidencia existe
            $stmt = $this->sql->prepare("SELECT id FROM Incident WHERE id = ?");
            $stmt->execute([$incidentId]);
            if (!$stmt->fetch()) {
                throw new \Exception("La incidencia no existe");
            }

            // Actualizar el estado
            $stmt = $this->sql->prepare("UPDATE Incident SET status = ? WHERE id = ?");
            if (!$stmt->execute([$status, $incidentId])) {
                throw new \Exception("Error al actualizar el estado");
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Error al actualizar el estado de la incidencia: " . $e->getMessage());
            throw new \Exception("Error al actualizar el estado: " . $e->getMessage());
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
                    i.status,
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
                $incident['status'] = in_array($incident['status'], ['pending', 'in_progress', 'resolved']) ? 
                    $incident['status'] : 'pending';
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
                    case 'pending': $stats['pending_incidents']++; break;
                    case 'in_progress': $stats['in_progress_incidents']++; break;
                    case 'resolved': $stats['resolved_incidents']++; break;
                }
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