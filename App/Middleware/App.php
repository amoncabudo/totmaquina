<?php

namespace App\Middleware;
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class App {

    public static function execute(Request $request, Response $response, Container $container, $next) :Response
    {
        // Asegurarnos de que la sesión esté iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si no existe la variable logat en la sesión, la inicializamos
        if (!isset($_SESSION["logat"])) {
            $_SESSION["logat"] = false;
        }

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