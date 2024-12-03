<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class CtrlAddIncidents
{
    public function register(Request $request, Response $response, Container $container) {
        // Obtener la conexión a la base de datos desde el contenedor
        $db = $container->get('db');
        
        // Recibir los datos del formulario
        $postData = $request->getPost();
        
        // Validar los campos requeridos
        $requiredFields = ['machine_id', 'issue', 'technician_id', 'date'];
        $error = $this->validateRequiredFields($requiredFields, $postData);
        if ($error) {
            $response->set('error', $error);
            return $response;
        }

        // Preparar los parámetros para la inserción en la base de datos
        $params = [
            ':description' => $postData['issue'],
            ':priority' => $postData['priority'] ?? 'medium',
            ':registered_date' => $postData['date'],
            ':responsible_technician_id' => $postData['technician_id'],
            ':machine_id' => $postData['machine_id'],
        ];

        // Insertar la incidencia en la base de datos
        try {
            $query = "
                INSERT INTO Incident (description, priority, registered_date, responsible_technician_id, machine_id) 
                VALUES (:description, :priority, :registered_date, :responsible_technician_id, :machine_id)
            ";
            $stmt = $db->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindParam($key, $value);
            }
            $stmt->execute();

            // Si todo va bien, devolver el mensaje de éxito
            $response->set('success', 'Incidencia registrada correctamente');
        } catch (\PDOException $e) {
            // Si ocurre un error, devolver el mensaje de error
            $response->set('error', 'Error al registrar la incidencia: ' . $e->getMessage());
        }

        return $response;
    }

    private function validateRequiredFields($requiredFields, $postData) {
        foreach ($requiredFields as $field) {
            if (empty($postData[$field])) {
                return "El campo $field es obligatorio.";
            }
        }
        return null;
    }
}
?>
