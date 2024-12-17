<?php
namespace App\Models;

class Maintenance {
    private $sql;
    private $machineModel;

    public function __construct($connection) {
        $this->sql = $connection;
        $this->machineModel = new Machine($connection);
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

    public function getAllTechnicians() {
        try {
            // Si el usuario es técnico, solo se muestra a sí mismo
            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] === 'technician') {
                $stmt = $this->sql->prepare("
                    SELECT 
                        id,
                        name,
                        surname,
                        email,
                        role
                    FROM User 
                    WHERE role = 'technician' 
                    AND id = ?
                    ORDER BY name, surname
                ");
                $stmt->execute([$_SESSION["user"]["id"]]);
            } 
            // Si es administrador o supervisor, muestra todos los técnicos
            else {
                $stmt = $this->sql->prepare("
                    SELECT 
                        id,
                        name,
                        surname,
                        email,
                        role
                    FROM User 
                    WHERE role IN ('technician', 'administrator', 'supervisor')
                    ORDER BY name, surname
                ");
                $stmt->execute();
            }
            
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Formatear los resultados para mostrar nombre completo
            return array_map(function($technician) {
                $technician['full_name'] = trim($technician['name'] . ' ' . $technician['surname']);
                return $technician;
            }, $results);
            
        } catch (\PDOException $e) {
            error_log("Error PDO en getAllTechnicians: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log("Error general en getAllTechnicians: " . $e->getMessage());
            throw $e;
        }
    }

    public function getAllMachines() {
        return $this->machineModel->getAllMachine();
    }

    public function getAllMaintenances() {
        try {
            $stmt = $this->sql->prepare("
                SELECT 
                    m.id,
                    m.scheduled_date,
                    m.frequency,
                    m.type,
                    m.status,
                    m.description,
                    ma.name as machine_name,
                    GROUP_CONCAT(
                        CONCAT(COALESCE(u.name, ''), ' ', COALESCE(u.surname, ''))
                        SEPARATOR ', '
                    ) as technicians
                FROM Maintenance m
                LEFT JOIN Machine ma ON m.machine_id = ma.id
                LEFT JOIN MaintenanceTechnician mt ON m.id = mt.maintenance_id
                LEFT JOIN User u ON mt.technician_id = u.id
                GROUP BY m.id, m.scheduled_date, m.frequency, m.type, m.status, m.description, ma.name
                ORDER BY m.scheduled_date DESC
            ");
            
            $stmt->execute();
            $maintenances = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Formatear las fechas y otros campos si es necesario
            return array_map(function($maintenance) {
                $maintenance['scheduled_date'] = date('Y-m-d H:i:s', strtotime($maintenance['scheduled_date']));
                $maintenance['status_text'] = $this->getStatusText($maintenance['status']);
                $maintenance['type_text'] = $this->getTypeText($maintenance['type']);
                $maintenance['frequency_text'] = $this->getFrequencyText($maintenance['frequency']);
                return $maintenance;
            }, $maintenances);
            
        } catch (\PDOException $e) {
            error_log("Error PDO en getAllMaintenances: " . $e->getMessage());
            throw $e;
        } catch (\Exception $e) {
            error_log("Error general en getAllMaintenances: " . $e->getMessage());
            throw $e;
        }
    }

    private function getStatusText($status) {
        $texts = [
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'completed' => 'Completado'
        ];
        return $texts[$status] ?? $status;
    }

    private function getTypeText($type) {
        $texts = [
            'preventive' => 'Preventivo',
            'corrective' => 'Correctivo'
        ];
        return $texts[$type] ?? $type;
    }

    private function getFrequencyText($frequency) {
        $texts = [
            'weekly' => 'Semanal',
            'monthly' => 'Mensual',
            'quarterly' => 'Trimestral',
            'yearly' => 'Anual'
        ];
        return $texts[$frequency] ?? $frequency;
    }

    public function assignTechnician($maintenanceId, $technicianId) {
        try {
            // Verificar si ya está asignado
            $stmt = $this->sql->prepare("
                SELECT 1 FROM MaintenanceTechnician 
                WHERE maintenance_id = ? AND technician_id = ?
            ");
            $stmt->execute([$maintenanceId, $technicianId]);
            
            if ($stmt->fetch()) {
                return true; // Ya está asignado
            }

            // Asignar el técnico
            $stmt = $this->sql->prepare("
                INSERT INTO MaintenanceTechnician (maintenance_id, technician_id)
                VALUES (?, ?)
            ");
            
            return $stmt->execute([$maintenanceId, $technicianId]);
            
        } catch (\Exception $e) {
            error_log("Error en assignTechnician: " . $e->getMessage());
            throw $e;
        }
    }

    public function removeTechnician($maintenanceId, $technicianId) {
        try {
            $stmt = $this->sql->prepare("
                DELETE FROM MaintenanceTechnician 
                WHERE maintenance_id = ? AND technician_id = ?
            ");
            
            return $stmt->execute([$maintenanceId, $technicianId]);
            
        } catch (\Exception $e) {
            error_log("Error en removeTechnician: " . $e->getMessage());
            throw $e;
        }
    }
} 