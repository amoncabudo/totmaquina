<?php
namespace App\Models;

class Maintenance {
    private $sql;

    public function __construct(\PDO $conn) {
        $this->sql = $conn;
    }
    
    public function create($data) {
        try {
            $sql = "INSERT INTO Maintenance (scheduled_date, frequency, type, status, responsible_technician_id, machine_id) 
                    VALUES (:scheduled_date, :frequency, :type, :status, :technician_id, :machine_id)";
            
            $stmt = $this->sql->prepare($sql);
            
            $params = [
                'scheduled_date' => $data['scheduled_date'],
                'frequency' => $data['frequency'],
                'type' => $data['type'],
                'status' => 'pending',
                'technician_id' => $data['technicians'][0] ?? null,
                'machine_id' => $data['machine_id']
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

    public function getAllMaintenances() {
        $stmt = $this->sql->prepare("SELECT m.*, ma.name as machine_name, u.name as technician_name 
                                    FROM Maintenance m 
                                    LEFT JOIN Machine ma ON m.machine_id = ma.id 
                                    LEFT JOIN User u ON m.responsible_technician_id = u.id 
                                    ORDER BY m.scheduled_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMaintenanceStats() {
        // Estadísticas por tipo
        $typeStats = $this->sql->prepare("
            SELECT type, COUNT(*) as count 
            FROM Maintenance 
            GROUP BY type
        ");
        $typeStats->execute();
        $byType = $typeStats->fetchAll(\PDO::FETCH_ASSOC);

        // Estadísticas por frecuencia
        $freqStats = $this->sql->prepare("
            SELECT frequency, COUNT(*) as count 
            FROM Maintenance 
            GROUP BY frequency
        ");
        $freqStats->execute();
        $byFrequency = $freqStats->fetchAll(\PDO::FETCH_ASSOC);

        // Estadísticas por mes
        $monthlyStats = $this->sql->prepare("
            SELECT DATE_FORMAT(scheduled_date, '%Y-%m') as month, COUNT(*) as count 
            FROM Maintenance 
            GROUP BY month 
            ORDER BY month DESC 
            LIMIT 12
        ");
        $monthlyStats->execute();
        $byMonth = $monthlyStats->fetchAll(\PDO::FETCH_ASSOC);

        // Estadísticas por máquina
        $machineStats = $this->sql->prepare("
            SELECT m.name as machine_name, COUNT(mt.id) as count 
            FROM Machine m 
            LEFT JOIN Maintenance mt ON m.id = mt.machine_id 
            GROUP BY m.id, m.name
        ");
        $machineStats->execute();
        $byMachine = $machineStats->fetchAll(\PDO::FETCH_ASSOC);

        return [
            'by_type' => $byType,
            'by_frequency' => $byFrequency,
            'by_month' => $byMonth,
            'by_machine' => $byMachine
        ];
    }
} 