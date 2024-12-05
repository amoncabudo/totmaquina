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
            $query = "SELECT i.registered_date as fecha, 
                            i.priority as tipo,
                            i.description as fallo,
                            i.status as reparacion,
                            TIMESTAMPDIFF(HOUR, i.registered_date, COALESCE(i.resolved_date, NOW())) as tiempo,
                            GROUP_CONCAT(CONCAT(u.name, ' ', u.surname) SEPARATOR ', ') as tecnicos
                     FROM Incident i
                     LEFT JOIN User u ON i.responsible_technician_id = u.id
                     WHERE i.machine_id = :machineId
                     GROUP BY i.id
                     ORDER BY i.registered_date DESC";

            $stmt = $this->sql->prepare($query);
            $stmt->execute(['machineId' => $machineId]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Procesar los resultados
            foreach ($results as &$row) {
                // Convertir la cadena de técnicos en array
                $row['tecnicos'] = $row['tecnicos'] ? explode(', ', $row['tecnicos']) : [];
                
                // Formatear la fecha
                $fecha = new \DateTime($row['fecha']);
                $row['fecha'] = $fecha->format('d/m/Y H:i');
                
                // Formatear el tiempo
                $row['tiempo'] = $row['tiempo'] . ' horas';
            }

            return $results;
        } catch (\PDOException $e) {
            error_log("Error en getIncidentHistory: " . $e->getMessage());
            throw new \Exception("Error al obtener el historial de incidencias");
        }
    }
} 