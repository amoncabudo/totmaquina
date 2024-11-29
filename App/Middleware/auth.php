<?php

namespace App\Middleware;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class Auth {

    public static function auth(Request $request, Response $response, Container $container) :Response
    {
        $logat = $request->get("SESSION", "logat");
        $user = $request->get("SESSION", "user");

        if ($logat !== true || !$user) {
            $response->setSession("error", "Debes iniciar sesión para acceder a esta página");
            $response->redirect("location: /login");
        }

        return $response;
    }

    public static function role($roles = []) {
        return function (Request $request, Response $response, Container $container) use ($roles) {
            $user = $request->get("SESSION", "user");
            
            if (!$user || !in_array($user['role'], $roles)) {
                $response->setSession("error", "No tienes permisos para acceder a esta página");
                $response->redirect("location: /index");
            }

            return $response;
        };
    }
}
