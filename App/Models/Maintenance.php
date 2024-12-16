<?php
namespace App\Models;

class Maintenance {
    private $sql;

    public function __construct($connection) {
        $this->sql = $connection;
    }

    public function getMaintenanceHistory($machineId) {
        try {
            error_log("Intentando obtener historial para máquina ID: " . $machineId);

            // Consulta adaptada a la estructura real de la tabla
            $stmt = $this->sql->prepare("
                SELECT 
                    m.id,
                    m.scheduled_date,
                    m.frequency,
                    m.type,
                    m.status,
                    m.description,
                    GROUP_CONCAT(
                        CONCAT(COALESCE(u.name, ''), ' ', COALESCE(u.surname, ''))
                    ) as technicians
                FROM Maintenance m
                LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                LEFT JOIN User u ON mt.technician_id = u.id
                WHERE m.machine_id = ?
                GROUP BY m.id
                ORDER BY m.scheduled_date DESC
            ");
            
            $stmt->execute([$machineId]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            error_log("Resultados obtenidos: " . print_r($results, true));

            return $results;
            
        } catch (\PDOException $e) {
            error_log("Error PDO en getMaintenanceHistory: " . $e->getMessage());
            error_log("Código de error: " . $e->getCode());
            throw $e;
        } catch (\Exception $e) {
            error_log("Error general en getMaintenanceHistory: " . $e->getMessage());
            throw $e;
        }
    }

    private function checkTableExists() {
        try {
            $stmt = $this->sql->prepare("
                SELECT 1 
                FROM information_schema.tables 
                WHERE table_schema = 'totmaquina' 
                AND table_name = 'Maintenance'
            ");
            $stmt->execute();
            $exists = $stmt->fetch() !== false;
            error_log("Verificación de tabla Maintenance: " . ($exists ? "existe" : "no existe"));
            return $exists;
        } catch (\Exception $e) {
            error_log("Error verificando existencia de tabla: " . $e->getMessage());
            return false;
        }
    }

    public function addMaintenance($data) {
        try {
            $this->sql->beginTransaction();

            // Insertar el mantenimiento
            $stmt = $this->sql->prepare("
                INSERT INTO Maintenance (
                    machine_id,
                    scheduled_date,
                    frequency,
                    type,
                    status,
                    description
                ) VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $data['machine_id'],
                $data['scheduled_date'],
                $data['frequency'],
                $data['type'],
                $data['status'] ?? 'pending',
                $data['description']
            ]);

            $maintenanceId = $this->sql->lastInsertId();

            // Si hay técnicos asignados, insertarlos en la tabla de relación
            if (!empty($data['technicians'])) {
                $stmt = $this->sql->prepare("
                    INSERT INTO MaintenanceTechnician (maintenance_id, technician_id)
                    VALUES (?, ?)
                ");

                foreach ($data['technicians'] as $technicianId) {
                    $stmt->execute([$maintenanceId, $technicianId]);
                }
            }

            $this->sql->commit();
            return true;

        } catch (\Exception $e) {
            $this->sql->rollBack();
            error_log("Error en addMaintenance: " . $e->getMessage());
            throw $e;
        }
    }
} 