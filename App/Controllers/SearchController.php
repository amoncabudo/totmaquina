<?php
namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class SearchController {
    public function search(Request $request, Response $response, Container $container) {
        // Obtener el parámetro de búsqueda 'query' del request
        $query = $request->get(INPUT_GET, 'query');
        
        // Validar si el parámetro de búsqueda es adecuado
        if (empty($query) || strlen($query) < 3) {
            $response->set('success', false);
            $response->set('error', 'La búsqueda debe tener al menos 3 caracteres');
            $response->setJson(); // Asegúrate de que setJson esté configurado correctamente
            return $response;
        }

        try {
            // Obtener la instancia de la base de datos desde el contenedor
            $db = $container->get("db");
            $searchQuery = "%" . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . "%";
            
            // Consulta SQL con parámetros preparados
            $sql = "SELECT id, name, model, manufacturer, location 
                    FROM Machine 
                    WHERE name LIKE ? 
                       OR model LIKE ? 
                       OR manufacturer LIKE ? 
                       OR location LIKE ?
                    LIMIT 10";
            
            // Preparar y ejecutar la consulta
            $stmt = $db->prepare($sql);
            $stmt->execute([$searchQuery, $searchQuery, $searchQuery, $searchQuery]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Devolver resultados en caso de éxito
            $response->set('success', true);
            $response->set('results', $results);
            $response->setJson(); // Asegúrate de que setJson esté configurado correctamente
            return $response;

        } catch (\Exception $e) {
            // Manejo de errores y respuesta en caso de fallo
            $response->set('success', false);
            $response->set('error', 'Error al realizar la búsqueda: ' . $e->getMessage());
            $response->setJson(); // Asegúrate de que setJson esté configurado correctamente
            return $response;
        }
    }
}
