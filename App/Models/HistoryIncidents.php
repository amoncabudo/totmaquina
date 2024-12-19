<?php
namespace App\Models;

class HistoryIncidents {
    private $sql;

    // Constructor to initialize the database connection
    public function __construct(\PDO $conn) {
        $this->sql = $conn;
    }

    // Function to get all machines
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

    // Function to get incident history for a specific machine
    public function getIncidentHistory($machineId) {
        try {
            $query = "SELECT 
                        i.id,
                        i.description,
                        i.priority,
                        CASE 
                            WHEN i.status = 'in_progress' THEN 'in progress'
                            WHEN i.status IS NULL THEN 'pending'
                            ELSE i.status 
                        END as status,
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

            error_log("=== INICIO getIncidentHistory ===");
            error_log("Query ejecutado: " . $query);
            error_log("MachineId: " . $machineId);
            error_log("Resultados sin procesar: " . print_r($results, true));

            // Process the results
            foreach ($results as &$row) {
                error_log("Procesando incidencia ID: " . $row['id']);
                error_log("Estado original: " . ($row['status'] ?? 'NULL'));

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
                error_log("Formateando estado para incidencia " . $row['id']);
                error_log("Estado antes de formatear: " . $row['status']);
                $row['status_text'] = [
                    'pending' => 'Pendiente',
                    'in progress' => 'En Proceso',
                    'resolved' => 'Resuelto'
                ][$row['status']] ?? $row['status'];
                error_log("Estado después de formatear: " . $row['status_text']);

                // Asegurar que technician_name tenga un valor por defecto
                $row['technician_name'] = $row['technician_name'] ?: 'Sin asignar';
            }

            error_log("Resultados finales: " . print_r($results, true));
            error_log("=== FIN getIncidentHistory ===");

            return $results;
        } catch (\PDOException $e) {
            error_log("Error en getIncidentHistory: " . $e->getMessage());
            throw new \Exception("Error al obtener el historial de incidencias");
        }
    }

    // Function to get machine information
    public function getMachineInfo($machineId) {
        try {
            $query = "SELECT 
                        m.*,
                        COUNT(i.id) as total_incidents,
                        SUM(CASE WHEN i.status = 'pending' THEN 1 ELSE 0 END) as pending_incidents,
                        SUM(CASE 
                            WHEN i.status = 'in progress' OR i.status = 'in_progress' 
                            THEN 1 ELSE 0 END) as in_progress_incidents,
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

            // Ensure the counters are numbers
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