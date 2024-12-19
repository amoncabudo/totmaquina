<?php
namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class SearchController {
    public function search(Request $request, Response $response, Container $container) {
        // Retrieve the 'query' search parameter from the request
        $query = $request->get(INPUT_GET, 'query');
        
        // Validate if the search parameter is adequate
        if (empty($query) || strlen($query) < 3) {
            // If the query is invalid (less than 3 characters), set failure response
            $response->set('success', false);
            $response->set('error', 'Search query must be at least 3 characters');
            $response->setJson(); // Ensure setJson is properly configured to return JSON
            return $response;
        }

        try {
            // Get the database instance from the container
            $db = $container->get("db");
            // Sanitize the search query to prevent SQL injection
            $searchQuery = "%" . htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . "%";
            
            // SQL query with prepared parameters for security
            $sql = "SELECT id, name, model, manufacturer, location 
                    FROM Machine 
                    WHERE name LIKE ? 
                       OR model LIKE ? 
                       OR manufacturer LIKE ? 
                       OR location LIKE ?
                    LIMIT 10";
            
            // Prepare and execute the query with parameters
            $stmt = $db->prepare($sql);
            $stmt->execute([$searchQuery, $searchQuery, $searchQuery, $searchQuery]);
            // Fetch all the results as an associative array
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Return results in case of success
            $response->set('success', true);
            $response->set('results', $results);
            $response->setJson(); // Ensure setJson is properly configured to return JSON
            return $response;

        } catch (\Exception $e) {
            // Handle errors and return a failure response if the search fails
            $response->set('success', false);
            $response->set('error', 'Error performing search: ' . $e->getMessage());
            $response->setJson(); // Ensure setJson is properly configured to return JSON
            return $response;
        }
    }
}
