<?php

namespace App\Middleware;
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class App {

    public static function execute(Request $request, Response $response, Container $container, $next) {
        try {
            if (!$response) {
                $response = $container->get("response");
            }

            // Configurar variables globales para las vistas
            $response->set("error", $request->get("SESSION", "error"));
            $response->set("success", $request->get("SESSION", "success"));
            
            // Limpiar mensajes de sesión después de usarlos
            $_SESSION["error"] = null;
            $_SESSION["success"] = null;

            // Ejecutar el siguiente middleware o controlador
            $nextResponse = \Emeset\Middleware::next($request, $response, $container, $next);
            
            // Asegurarse de que siempre devolvemos una respuesta válida
            return $nextResponse instanceof Response ? $nextResponse : $response;

        } catch (\Exception $e) {
            error_log("Error en App middleware: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());

            if ($request->isAjax()) {
                return $response
                    ->setHeader('Content-Type', 'application/json')
                    ->setStatus(500)
                    ->setJson([
                        'success' => false,
                        'message' => 'Error interno del servidor'
                    ]);
            }

            // En caso de error, mostrar la página de error
            return $response->setTemplate("error.php");
        }
    }
}