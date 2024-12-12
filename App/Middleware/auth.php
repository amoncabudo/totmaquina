<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Middleware que gestiona la autenticación
 *
 * @param \Emeset\Contracts\Http\Request $request petición HTTP
 * @param \Emeset\Contracts\Http\Response $response respuesta HTTP
 * @param \Emeset\Contracts\Container $container  
 * @param callable $next siguiente middleware o controlador.   
 * @return \Emeset\Contracts\Http\Response respuesta HTTP
 */
function auth(Request $request, Response $response, Container $container, $next) : Response
{
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
        $response = \Emeset\Middleware::next($request, $response, $container, $next);
    } else {
        $response->setSession("error", "No tienes permisos para acceder a esta página");
        $response->redirect("location: /login");
    }
    return $response;
}

/**
 * Middleware que gestiona los roles
 */
function role($roles = []) {
    return function (Request $request, Response $response, Container $container, $next) use ($roles) {
        $user = $request->get("SESSION", "user");
        
        if (!$user || !isset($user['role']) || !in_array($user['role'], $roles)) {
            error_log("Role Middleware - Acceso denegado para rol: " . ($user['role'] ?? 'no definido'));
            $response->setSession("error", "No tienes permisos para acceder a esta página");
            $response->redirect("location: /login");
            return $response;
        }

        return \Emeset\Middleware::next($request, $response, $container, $next);
    };
}