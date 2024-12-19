<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class TestUserController {
    
    public function createTestUser(Request $request, Response $response, Container $container) {
        try {
            // Get data from the POST request
            $nombre = $request->get(INPUT_POST, 'nombre'); // User's first name
            $apellido = $request->get(INPUT_POST, 'apellido'); // User's last name
            $email = $request->get(INPUT_POST, 'email'); // User's email
            $password = $request->get(INPUT_POST, 'pass'); // User's password
            $role = $request->get(INPUT_POST, 'rol'); // User's role (e.g., admin, user)
            
            // Log received data for debugging purposes
            error_log("Received data: " . json_encode([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'role' => $role
            ]));

            // Validate that all required fields are present
            if (!$nombre || !$apellido || !$email || !$password || !$role) {
                // Respond with an error if any required field is missing
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Required fields are missing'
                ]));
                return $response;
            }

            // Get the user model from the container
            $userModel = $container->get("User");
            
            // Insert user into the database using the existing method
            $userId = $userModel->insertUser(
                $nombre, 
                $apellido, 
                $email, 
                $password, 
                $role, 
                'default.png' // Default avatar image
            );

            // Respond with success and the created user's ID
            $response->setBody(json_encode([
                'success' => true,
                'message' => 'User created successfully',
                'userId' => $userId // Return the ID of the created user
            ]));

        } catch (\Exception $e) {
            // Log the error if something goes wrong
            error_log('Error in createTestUser: ' . $e->getMessage());
            // Respond with an error message in case of an exception
            $response->setBody(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
        }

        // Ensure that a response is always returned
        return $response;
    }
}
