<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Middleware que gestiona el acceso para administradores
 *
 * @param \Emeset\Contracts\Http\Request $request petición HTTP
 * @param \Emeset\Contracts\Http\Response $response respuesta HTTP
 * @param \Emeset\Contracts\Container $container  
 * @param callable $next siguiente middleware o controlador.   
 * @return \Emeset\Contracts\Http\Response respuesta HTTP
 */
function administrator(Request $request, Response $response, Container $container, $next) : Response
{
    $user = $request->get("SESSION", "user", FILTER_REQUIRE_ARRAY);
    $logat = $request->get("SESSION", "logat");
    
    // Si el usuario es administrador y está logueado
    if (is_array($user) && $user['role'] === 'administrator' && $logat) {
        $response->set("user", $user);
        $response->set("logat", $logat);
        $response = \Emeset\Middleware::next($request, $response, $container, $next);
    } else {
        $response->setSession("error", "Necesitas ser administrador para acceder a esta página");
        $response->redirect("location: /login");
    }
    return $response;
} 