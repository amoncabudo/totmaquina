<?php

namespace App\Models;

class Machine
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el modelo de máquinas
     * 
     * @param \PDO $conn conexión a la base de datos
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * getAllMachine: Obtener todas las máquinas
     * 
     * @return array
     */
    public function getAllMachine(){
        try {
            $stmt = $this->sql->prepare("SELECT id, name, model, manufacturer, coordinates,  location FROM Machine ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getAllMachine: " . $e->getMessage());
            throw new \Exception("Error al obtener las máquinas");
        }
    }

    public function getMachineById($id) {
        try {
            $stmt = $this->sql->prepare("SELECT * FROM Machine WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getMachineById: " . $e->getMessage());
            throw new \Exception("Error al obtener la máquina");
        }
    }

    /**
     * updateMachine: Actualizar una máquina
     * 
     * @param int $id
     * @param string $name
     * @param string $model
     * @param string $manufacturer
     * @param string $location
     * @param string $installation_date
     * @param string $serial_number
     * @param string $photo
     * @param string $coordinates
     */
    public function updateMachine($id, $name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates)
    {
        $query = "UPDATE Machine SET name = :name, model = :model, manufacturer = :manufacturer, location = :location, installation_date = :installation_date, serial_number = :serial_number, photo = :photo, coordinates = :coordinates WHERE id = :id";
        $stmt = $this->sql->prepare($query);
        
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'model' => $model,
            'manufacturer' => $manufacturer,
            'location' => $location,
            'installation_date' => $installation_date,
            'serial_number' => $serial_number,
            'photo' => $photo,
            'coordinates' => $coordinates
        ]);
    }

    /**
     * insertMachine: Insertar una nueva máquina
     * 
     * @param string $name
     * @param string $model
     * @param string $manufacturer
     * @param string $location
     * @param string $installation_date
     * @param string $serial_number
     * @param string $photo
     * @param string $coordinates
     */
    public function insertMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates)
    {
        try {
            $query = "INSERT INTO Machine (name, model, manufacturer, location, installation_date, serial_number, photo, coordinates) VALUES (:name, :model, :manufacturer, :location, :installation_date, :serial_number, :photo, :coordinates)";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'name' => $name,
                'model' => $model,
                'manufacturer' => $manufacturer,
                'location' => $location,
                'installation_date' => $installation_date,
                'serial_number' => $serial_number,
                'photo' => $photo,
                'coordinates' => $coordinates
            ]);
        } catch (\PDOException $e) {
            echo "Error en la inserción: " . $e->getMessage();
            exit;
        }
    }

    /**
     * deleteMachine: Eliminar una máquina por su ID
     * 
     * @param int $id
     */
    public function deleteMachine($id)
    {
        $query = "DELETE FROM Machine WHERE id = :id";
        $stmt = $this->sql->prepare($query);
        $stmt->execute([":id" => $id]);
    
        if ($stmt->errorCode() !== '00000') {
            $err = $stmt->errorInfo();
            die("Error al eliminar: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }

    /**
     * assignUserToMachine: Asignar un usuario a una máquina
     * 
     * @param int $machineId
     * @param int $userId
     */
    public function assignUserToMachine($machineId, $userId)
    {
        $query = "UPDATE Machine SET assigned_technician_id = :userId WHERE id = :machineId";
        $stmt = $this->sql->prepare($query);
        $stmt->execute([
            ':machineId' => $machineId,
            ':userId' => $userId
        ]);

        if ($stmt->errorCode() !== '00000') {
            $err = $stmt->errorInfo();
            die("Error al asignar usuario: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }

    /**
     * getMachineBySerialNumber: Obtener una máquina por su número de serie
     * 
     * @param string $serial_number
     * @return array
     */
    public function getMachineBySerialNumber($serial_number)
    {
        $query = "SELECT * FROM Machine WHERE serial_number = :serial_number";
        $stmt = $this->sql->prepare($query);
        $stmt->execute(['serial_number' => $serial_number]);
        return $stmt->fetch();
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

    public function getMachinesByTechnician($technicianId) {
        try {
            $sql = "SELECT DISTINCT m.* 
                    FROM Machine m 
                    LEFT JOIN Incident i ON m.id = i.machine_id 
                    LEFT JOIN Maintenance mt ON m.id = mt.machine_id 
                    LEFT JOIN MaintenanceTechnician mtt ON mt.id = mtt.maintenance_id 
                    WHERE m.assigned_technician_id = ? 
                       OR i.responsible_technician_id = ?
                       OR mtt.technician_id = ?
                    ORDER BY m.name ASC";
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute([$technicianId, $technicianId, $technicianId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getMachinesByTechnician: " . $e->getMessage());
            return [];
        }
    }

    public function getIncidentsByTechnicianAndMachine($technicianId, $machineId) {
        try {
            $params = [];
            $sql = "SELECT i.id, 
                           i.description,
                           i.priority,
                           CASE 
                               WHEN i.status = 'in_progress' THEN 'in progress'
                               WHEN i.status IS NULL THEN 'pending'
                               ELSE i.status 
                           END as status,
                           DATE_FORMAT(i.registered_date, '%Y-%m-%d %H:%i:%s') as registered_date,
                           i.resolved_date,
                           i.machine_id,
                           i.responsible_technician_id,
                           m.name as machine_name,
                           CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name
                    FROM Incident i 
                    LEFT JOIN Machine m ON i.machine_id = m.id
                    LEFT JOIN User u ON i.responsible_technician_id = u.id
                    WHERE i.responsible_technician_id = ?";
            $params[] = $technicianId;
            
            if ($machineId !== null) {
                $sql .= " AND i.machine_id = ?";
                $params[] = $machineId;
            }
            
            $sql .= " ORDER BY i.registered_date DESC";
            
            error_log("SQL Query: " . $sql);
            error_log("Params: " . print_r($params, true));
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute($params);
            $incidents = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Asegurar que todos los estados sean válidos
            foreach ($incidents as &$incident) {
                if (empty($incident['status'])) {
                    $incident['status'] = 'pending';
                }
            }
            
            error_log("Incidencias recuperadas: " . print_r($incidents, true));
            
            return $incidents;
        } catch (\PDOException $e) {
            error_log("Error en getIncidentsByTechnicianAndMachine: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function getMaintenanceHistory($machineId) {
        try {
            $sql = "SELECT m.*, u.name as technician_name 
                    FROM Maintenance m 
                    LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                    LEFT JOIN User u ON mt.technician_id = u.id 
                    WHERE m.machine_id = ? 
                    ORDER BY m.scheduled_date DESC";
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute([$machineId]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getMaintenanceHistory: " . $e->getMessage());
            return [];
        }
    }

    public function getMachineIncidents($machineId) {
        try {
            $sql = "SELECT i.id, 
                           i.description,
                           i.priority,
                           CASE 
                               WHEN i.status = 'in_progress' THEN 'in progress'
                               WHEN i.status IS NULL THEN 'pending'
                               ELSE i.status 
                           END as status,
                           DATE_FORMAT(i.registered_date, '%Y-%m-%d %H:%i:%s') as registered_date,
                           i.resolved_date,
                           i.responsible_technician_id,
                           CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name 
                    FROM Incident i 
                    LEFT JOIN User u ON i.responsible_technician_id = u.id 
                    WHERE i.machine_id = ? 
                    ORDER BY i.registered_date DESC";
            
            error_log("SQL Query for machine incidents: " . $sql);
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute([$machineId]);
            $incidents = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Asegurar que todos los estados sean válidos
            foreach ($incidents as &$incident) {
                if (empty($incident['status'])) {
                    $incident['status'] = 'pending';
                }
            }
            
            error_log("Machine incidents retrieved: " . print_r($incidents, true));
            
            return $incidents;
        } catch (\PDOException $e) {
            error_log("Error en getMachineIncidents: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return [];
        }
    }

    public function getMaintenanceByTechnician($technicianId, $machineId = null) {
        try {
            $params = [$technicianId];
            $sql = "SELECT DISTINCT m.* 
                    FROM Maintenance m 
                    INNER JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id 
                    WHERE mt.technician_id = ?";
            
            if ($machineId !== null) {
                $sql .= " AND m.machine_id = ?";
                $params[] = $machineId;
            }
            
            $sql .= " ORDER BY m.scheduled_date DESC";
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getMaintenanceByTechnician: " . $e->getMessage());
            return [];
        }
    }
}

