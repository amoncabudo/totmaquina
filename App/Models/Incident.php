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
            $sql = "INSERT INTO Incident (description, priority, status, registered_date, machine_id, responsible_technician_id) 
                    VALUES (:description, :priority, :status, NOW(), :machine_id, :technician_id)";
            
            $stmt = $this->sql->prepare($sql);
            
            $params = [
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => 'pending',
                'machine_id' => $data['machine_id'],
                'technician_id' => $data['technicians'][0] ?? null
            ];
            
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            echo "Error en la inserción: " . $e->getMessage();
            exit;
        }
    }

    public function getAllTechnicians() {
        $stmt = $this->sql->prepare("SELECT id, name, surname FROM User WHERE role = 'technician'");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllMachines() {
        $stmt = $this->sql->prepare("SELECT id, name FROM Machine");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
}