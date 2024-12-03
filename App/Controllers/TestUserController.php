<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class TestUserController {
    
    public function createTestUser(Request $request, Response $response, Container $container) {
        try {
            // Obtener datos del POST
            $nombre = $request->get(INPUT_POST, 'nombre');
            $apellido = $request->get(INPUT_POST, 'apellido');
            $email = $request->get(INPUT_POST, 'email');
            $password = $request->get(INPUT_POST, 'pass');
            $role = $request->get(INPUT_POST, 'rol');
            
            // Log para debugging
            error_log("Datos recibidos: " . json_encode([
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'role' => $role
            ]));

            // Validar que todos los campos necesarios estén presentes
            if (!$nombre || !$apellido || !$email || !$password || !$role) {
                $response->setBody(json_encode([
                    'success' => false,
                    'message' => 'Faltan campos requeridos'
                ]));
                return $response;
            }

            // Obtener el modelo de usuario
            $userModel = $container->get("User");
            
            // Insertar usuario usando el método existente
            $userId = $userModel->insertUser(
                $nombre,
                $apellido,
                $email,
                $password,
                $role,
                'default.png' // Avatar por defecto
            );

            $response->setBody(json_encode([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'userId' => $userId // Devolver el ID del usuario creado
            ]));

        } catch (\Exception $e) {
            error_log('Error en createTestUser: ' . $e->getMessage());
            $response->setBody(json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]));
        }

        // Asegurarse de que siempre devolvemos una respuesta
        return $response;
    }
} 