<?php
namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class SearchController {
    public function search(Request $request, Response $response, Container $container) {
        $query = $request->get(INPUT_GET, 'query');
        
        if (empty($query) || strlen($query) < 3) {
            $response->set('success', false);
            $response->set('error', 'La búsqueda debe tener al menos 3 caracteres');
            $response->setJson();
            return $response;
        }

        try {
            $db = $container->get("db");
            $searchQuery = "%" . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . "%";
            
            $sql = "SELECT id, name, model, manufacturer, location 
                   FROM Machine 
                   WHERE name LIKE ? 
                      OR model LIKE ? 
                      OR manufacturer LIKE ? 
                      OR location LIKE ?
                   LIMIT 10";
            
            $stmt = $db->prepare($sql);
            $stmt->execute([$searchQuery, $searchQuery, $searchQuery, $searchQuery]);
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $response->set('success', true);
            $response->set('results', $results);
            $response->setJson();
            return $response;

        } catch (\Exception $e) {
            $response->set('success', false);
            $response->set('error', 'Error al realizar la búsqueda');
            $response->setJson();
            return $response;
        }
    }
} 