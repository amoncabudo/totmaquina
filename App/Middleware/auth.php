<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Middleware que gestiona la autenticación
 */
function auth(Request $request, Response $response, Container $container, $next) : Response
{
    if (!$response) {
        $response = $container->get('response');
    }

    $user = $request->get("SESSION", "user");
    $logat = $request->get("SESSION", "logat");

    if (!isset($logat)) {
        $user = "";
        $logat = false;
    }

    $response->set("user", $user);
    $response->set("logat", $logat);

    // Si el usuario está logueado permitimos cargar el recurso
    if ($logat) {
        try {
            $nextResponse = \Emeset\Middleware::next($request, $response, $container, $next);
            return $nextResponse instanceof Response ? $nextResponse : $response;
        } catch (\Exception $e) {
            error_log("Error en auth middleware: " . $e->getMessage());
            return $response->setTemplate("error.php");
        }
    }

    // Si es una petición AJAX, devolver error JSON
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        return $response->setJson([
            'success' => false,
            'message' => 'No autorizado',
            'redirect' => '/login'
        ])->setStatus(401);
    }
    
    // Si no es AJAX, redirigir al login
    $response->setSession("error", "No tienes permisos para acceder a esta página");
    return $response->redirect("/login");
}

/**
 * Middleware que gestiona los roles
 */
function role($roles = []) {
    return function (Request $request, Response $response, Container $container, $next) use ($roles) {
        if (!$response) {
            $response = $container->get('response');
        }

        $user = $request->get("SESSION", "user");
        
        if (!$user || !isset($user['role']) || !in_array($user['role'], $roles)) {
            error_log("Role Middleware - Acceso denegado para rol: " . ($user['role'] ?? 'no definido'));
            $response->setSession("error", "No tienes permisos para acceder a esta página");
            return $response->redirect("/login");
        }

        try {
            $nextResponse = \Emeset\Middleware::next($request, $response, $container, $next);
            return $nextResponse instanceof Response ? $nextResponse : $response;
        } catch (\Exception $e) {
            error_log("Error en role middleware: " . $e->getMessage());
            return $response->setTemplate("error.php");
        }
    };
}