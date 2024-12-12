<?php
namespace App\Models;

class Maintenance {
    private $sql;

    public function __construct(\PDO $conn) {
        $this->sql = $conn;
    }
    
    public function create($data) {
        try {
            $this->sql->beginTransaction();

            // Debug: Imprimir los datos recibidos
            error_log("Datos recibidos en create:");
            error_log(print_r($data, true));

            // Validar la fecha
            if (!strtotime($data['scheduled_date'])) {
                throw new \Exception("Fecha inválida");
            }

            // Insertar el mantenimiento
            $sql = "INSERT INTO Maintenance (scheduled_date, frequency, type, status, machine_id, description) 
                    VALUES (:scheduled_date, :frequency, :type, :status, :machine_id, :description)";
            
            $stmt = $this->sql->prepare($sql);
            
            $params = [
                'scheduled_date' => $data['scheduled_date'],
                'frequency' => $data['frequency'],
                'type' => $data['type'],
                'status' => 'pending',
                'machine_id' => $data['machine_id'],
                'description' => $data['description'] ?? null
            ];
            
            error_log("Ejecutando inserción de mantenimiento con parámetros:");
            error_log(print_r($params, true));
            
            $stmt->execute($params);
            $maintenanceId = $this->sql->lastInsertId();

            // Procesar los técnicos asignados
            if (!empty($data['technicians'])) {
                // Primero eliminar asignaciones existentes para este mantenimiento
                $deleteStmt = $this->sql->prepare("DELETE FROM MaintenanceTechnician WHERE maintenance_id = ?");
                $deleteStmt->execute([$maintenanceId]);
                
                // Convertir a array si es una cadena o si es un único valor
                $technicians = is_array($data['technicians']) ? $data['technicians'] : [$data['technicians']];
                
                error_log("Procesando técnicos:");
                error_log(print_r($technicians, true));
                
                // Preparar la consulta de inserción una sola vez
                $insertStmt = $this->sql->prepare(
                    "INSERT IGNORE INTO MaintenanceTechnician (maintenance_id, technician_id) 
                     VALUES (:maintenance_id, :technician_id)"
                );
                
                foreach ($technicians as $technicianId) {
                    if (!empty($technicianId)) {
                        $insertStmt->execute([
                            'maintenance_id' => $maintenanceId,
                            'technician_id' => $technicianId
                        ]);
                        error_log("Técnico $technicianId asignado al mantenimiento $maintenanceId");
                    }
                }
            }

            $this->sql->commit();
            return true;
        } catch (\PDOException $e) {
            $this->sql->rollBack();
            error_log("Error en create maintenance: " . $e->getMessage());
            throw new \Exception("Error al crear el mantenimiento: " . $e->getMessage());
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
        try {
            $sql = "SELECT m.*, ma.name as machine_name,
                    GROUP_CONCAT(CONCAT(u.name, ' ', u.surname) SEPARATOR ', ') as technicians
                    FROM Maintenance m 
                    LEFT JOIN Machine ma ON m.machine_id = ma.id
                    LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                    LEFT JOIN User u ON mt.technician_id = u.id 
                    GROUP BY m.id
                    ORDER BY m.scheduled_date DESC";
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getAllMaintenances: " . $e->getMessage());
            throw new \Exception("Error al obtener los mantenimientos");
        }
    }

    public function getMaintenanceHistory($machineId) {
        try {
            $sql = "SELECT 
                    m.id,
                    m.scheduled_date,
                    m.type,
                    m.status,
                    m.description,
                    ma.name as machine_name, 
                    ma.model as machine_model,
                    GROUP_CONCAT(DISTINCT CONCAT(u.name, ' ', u.surname) SEPARATOR ', ') as technicians
                    FROM Maintenance m 
                    LEFT JOIN Machine ma ON m.machine_id = ma.id
                    LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                    LEFT JOIN User u ON mt.technician_id = u.id 
                    WHERE m.machine_id = :machine_id 
                    GROUP BY m.id, m.scheduled_date, m.type, m.status, m.description, ma.name, ma.model
                    ORDER BY m.scheduled_date DESC";
            
            $stmt = $this->sql->prepare($sql);
            $stmt->execute(['machine_id' => $machineId]);
            
            $history = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Formatear los datos
            $formattedHistory = array_map(function($record) {
                return [
                    'date' => date('Y-m-d', strtotime($record['scheduled_date'])),
                    'type' => $this->translateMaintenanceType($record['type']),
                    'status' => $this->translateStatus($record['status']),
                    'description' => $record['description'] ?: 'Sin descripción',
                    'technician' => $record['technicians'] ?: 'No asignado',
                    'machine_name' => $record['machine_name'],
                    'machine_model' => $record['machine_model']
                ];
            }, $history);
            
            return $formattedHistory;
        } catch (\PDOException $e) {
            error_log("Error en getMaintenanceHistory: " . $e->getMessage());
            throw new \Exception("Error al obtener el historial de mantenimiento");
        }
    }

    private function translateMaintenanceType($type) {
        $types = [
            'preventive' => 'Preventivo',
            'corrective' => 'Correctivo'
        ];
        return $types[$type] ?? $type;
    }

    private function translateStatus($status) {
        $statuses = [
            'pending' => 'Pendiente',
            'in_progress' => 'En Proceso',
            'completed' => 'Completado'
        ];
        return $statuses[$status] ?? $status;
    }

    public function getMaintenanceStats() {
        try {
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

            // Estadísticas por mes (últimos 12 meses)
            $monthlyStats = $this->sql->prepare("
                SELECT DATE_FORMAT(scheduled_date, '%Y-%m') as month, COUNT(*) as count 
                FROM Maintenance 
                WHERE scheduled_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY month 
                ORDER BY month DESC
            ");
            $monthlyStats->execute();
            $byMonth = $monthlyStats->fetchAll(\PDO::FETCH_ASSOC);

            // Estadísticas por máquina
            $machineStats = $this->sql->prepare("
                SELECT m.name as machine_name, COUNT(mt.id) as count 
                FROM Machine m 
                LEFT JOIN Maintenance mt ON m.id = mt.machine_id 
                GROUP BY m.id, m.name
                ORDER BY count DESC
            ");
            $machineStats->execute();
            $byMachine = $machineStats->fetchAll(\PDO::FETCH_ASSOC);

            // Estadísticas por estado
            $statusStats = $this->sql->prepare("
                SELECT status, COUNT(*) as count 
                FROM Maintenance 
                GROUP BY status
            ");
            $statusStats->execute();
            $byStatus = $statusStats->fetchAll(\PDO::FETCH_ASSOC);

            // Estadísticas de técnicos más activos
            $technicianStats = $this->sql->prepare("
                SELECT CONCAT(u.name, ' ', u.surname) as technician_name, 
                       COUNT(DISTINCT mt.maintenance_id) as count
                FROM User u
                LEFT JOIN MaintenanceTechnician mt ON u.id = mt.technician_id
                WHERE u.role = 'technician'
                GROUP BY u.id
                ORDER BY count DESC
            ");
            $technicianStats->execute();
            $byTechnician = $technicianStats->fetchAll(\PDO::FETCH_ASSOC);

            return [
                'by_type' => $byType,
                'by_frequency' => $byFrequency,
                'by_month' => $byMonth,
                'by_machine' => $byMachine,
                'by_status' => $byStatus,
                'by_technician' => $byTechnician
            ];
        } catch (\PDOException $e) {
            error_log("Error en getMaintenanceStats: " . $e->getMessage());
            throw new \Exception("Error al obtener las estadísticas de mantenimiento");
        }
    }

    /**
     * Actualiza el estado de un mantenimiento
     * 
     * @param int $maintenanceId ID del mantenimiento
     * @param string $status Nuevo estado ('pending', 'in_progress', 'completed')
     * @return bool
     * @throws \Exception
     */
    public function updateMaintenanceStatus($maintenanceId, $status) {
        try {
            // Validar el estado
            $validStatuses = ['pending', 'in_progress', 'completed'];
            if (!in_array($status, $validStatuses)) {
                throw new \Exception("Estado no válido");
            }

            // Verificar que el mantenimiento existe
            $stmt = $this->sql->prepare("SELECT id FROM Maintenance WHERE id = ?");
            $stmt->execute([$maintenanceId]);
            if (!$stmt->fetch()) {
                throw new \Exception("El mantenimiento no existe");
            }

            // Actualizar el estado
            $stmt = $this->sql->prepare("UPDATE Maintenance SET status = ? WHERE id = ?");
            if (!$stmt->execute([$status, $maintenanceId])) {
                throw new \Exception("Error al actualizar el estado");
            }

            return true;
        } catch (\PDOException $e) {
            error_log("Error al actualizar el estado del mantenimiento: " . $e->getMessage());
            throw new \Exception("Error al actualizar el estado: " . $e->getMessage());
        }
    }
} 