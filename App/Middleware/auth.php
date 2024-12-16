<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Middleware de autenticación
 */
function auth(Request $request, Response $response, Container $container, $next) {
    try {
        // Verificar si hay una sesión activa
        $user = $request->get("SESSION", "user");
        $logat = $request->get("SESSION", "logat");

        if (!isset($logat) || !$logat) {
            // Si no hay sesión y es una petición AJAX, devolver error JSON
            if ($request->isAjax()) {
                return $response
                    ->setHeader('Content-Type', 'application/json')
                    ->setStatus(401)
                    ->setJson([
                        'success' => false,
                        'message' => 'No autorizado'
                    ]);
            }
            // Si no es AJAX, redirigir al login
            return $response->redirect("/login");
        }

        // Si hay sesión, continuar
        $response->set("user", $user);
        $response->set("logat", $logat);

        // Ejecutar el siguiente middleware o controlador
        return \Emeset\Middleware::next($request, $response, $container, $next);
        
    } catch (\Exception $e) {
        error_log("Error en auth middleware: " . $e->getMessage());
        
        if ($request->isAjax()) {
            return $response
                ->setHeader('Content-Type', 'application/json')
                ->setStatus(500)
                ->setJson([
                    'success' => false,
                    'message' => 'Error de autenticación'
                ]);
        }
        
        return $response->redirect("/login");
    }
}

/**
 * Middleware que gestiona los roles
 */
function role($roles = []) {
    return function (Request $request, Response $response, Container $container, $next) use ($roles) {
        $user = $request->get("SESSION", "user");
        
        if (!$user || !isset($user['role']) || !in_array($user['role'], $roles)) {
            if ($request->isAjax()) {
                return $response
                    ->setHeader('Content-Type', 'application/json')
                    ->setStatus(403)
                    ->setJson([
                        'success' => false,
                        'message' => 'No tienes permisos para acceder a esta página'
                    ]);
            }
            
            $response->setSession("error", "No tienes permisos para acceder a esta página");
            return $response->redirect("/login");
        }

        return \Emeset\Middleware::next($request, $response, $container, $next);
    };
}