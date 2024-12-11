<?php

namespace App\Middleware;
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class App {

    public static function execute(Request $request, Response $response, Container $container, $next) :Response
    {
        // Asegurarnos que la sesión está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Inicializar variables de sesión si no existen
        if (!isset($_SESSION['logat'])) {
            $_SESSION['logat'] = false;
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = null;
        }

        // Sincronizar los valores con el response
        $response->set("logat", $_SESSION['logat']);
        $response->set("user", $_SESSION['user']);

        // Continuar con la ejecución
        if (is_array($next)) {
            if (count($next) > 1) {
                $call = array_shift($next);
                $response = call_user_func($call, $request, $response, $container, $next);
            } else {
                $response = call_user_func($next[0], $request, $response, $container);
            }
        } else {
            $response = $next($request, $response, $container);
        }

        return $response;
    }
}