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
            if (!empty($data['technicians'])) {
                $technicianId = (int)$data['technicians'];
                
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
            // Si el usuario es técnico, solo mostrar máquinas sin técnico asignado
            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'technician') {
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
}