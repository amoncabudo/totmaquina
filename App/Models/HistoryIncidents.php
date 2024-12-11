<?php
namespace App\Models;

class HistoryIncidents {
    private $sql;

    public function __construct(\PDO $conn) {
        $this->sql = $conn;
    }

    public function getAllMachines() {
        try {
            $stmt = $this->sql->prepare("SELECT id, name FROM Machine ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error en getAllMachines: " . $e->getMessage());
            throw new \Exception("Error al obtener la lista de máquinas");
        }
    }

    public function getIncidentHistory($machineId) {
        try {
            $query = "SELECT 
                        i.id,
                        i.description,
                        i.priority,
                        i.status,
                        i.registered_date,
                        i.resolved_date,
                        TIMESTAMPDIFF(HOUR, i.registered_date, COALESCE(i.resolved_date, NOW())) as tiempo,
                        m.name as machine_name,
                        CONCAT(u.name, ' ', COALESCE(u.surname, '')) as technician_name
                    FROM Incident i
                    LEFT JOIN Machine m ON i.machine_id = m.id
                    LEFT JOIN User u ON i.responsible_technician_id = u.id
                    WHERE i.machine_id = :machineId
                    ORDER BY i.registered_date DESC";

            $stmt = $this->sql->prepare($query);
            $stmt->execute(['machineId' => $machineId]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            error_log("Resultados de la consulta para máquina $machineId: " . print_r($results, true));

            // Procesar los resultados
            foreach ($results as &$row) {
                // Formatear la fecha de registro
                $fecha = new \DateTime($row['registered_date']);
                $row['registered_date'] = $fecha->format('d/m/Y H:i');

                // Formatear la fecha de resolución si existe
                if ($row['resolved_date']) {
                    $fechaResolucion = new \DateTime($row['resolved_date']);
                    $row['resolved_date'] = $fechaResolucion->format('d/m/Y H:i');
                }

                // Formatear el tiempo
                $horas = (int)$row['tiempo'];
                if ($horas < 24) {
                    $row['tiempo'] = $horas . ' horas';
                } else {
                    $dias = floor($horas / 24);
                    $horasRestantes = $horas % 24;
                    $row['tiempo'] = $dias . ' días ' . ($horasRestantes > 0 ? $horasRestantes . ' horas' : '');
                }

                // Formatear la prioridad
                $row['priority_text'] = [
                    'high' => 'Alta',
                    'medium' => 'Media',
                    'low' => 'Baja'
                ][$row['priority']] ?? $row['priority'];

                // Formatear el estado
                $row['status_text'] = [
                    'pending' => 'Pendiente',
                    'in_progress' => 'En Proceso',
                    'resolved' => 'Resuelto'
                ][$row['status']] ?? $row['status'];

                // Asegurar que technician_name tenga un valor por defecto
                $row['technician_name'] = $row['technician_name'] ?: 'Sin asignar';
            }

            return $results;
        } catch (\PDOException $e) {
            error_log("Error en getIncidentHistory: " . $e->getMessage());
            throw new \Exception("Error al obtener el historial de incidencias");
        }
    }

    public function getMachineInfo($machineId) {
        try {
            $query = "SELECT 
                        m.*,
                        COUNT(i.id) as total_incidents,
                        SUM(CASE WHEN i.status = 'pending' THEN 1 ELSE 0 END) as pending_incidents,
                        SUM(CASE WHEN i.status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_incidents,
                        SUM(CASE WHEN i.status = 'resolved' THEN 1 ELSE 0 END) as resolved_incidents
                    FROM Machine m
                    LEFT JOIN Incident i ON m.id = i.machine_id
                    WHERE m.id = :machineId
                    GROUP BY m.id";

            $stmt = $this->sql->prepare($query);
            $stmt->execute(['machineId' => $machineId]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$result) {
                throw new \Exception("Máquina no encontrada");
            }

            // Asegurar que los contadores sean números
            $result['total_incidents'] = (int)$result['total_incidents'];
            $result['pending_incidents'] = (int)$result['pending_incidents'];
            $result['in_progress_incidents'] = (int)$result['in_progress_incidents'];
            $result['resolved_incidents'] = (int)$result['resolved_incidents'];

            error_log("Información de la máquina $machineId: " . print_r($result, true));

            return $result;
        } catch (\PDOException $e) {
            error_log("Error en getMachineInfo: " . $e->getMessage());
            throw new \Exception("Error al obtener la información de la máquina");
        }
    }
} 