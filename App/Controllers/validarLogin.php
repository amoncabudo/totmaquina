<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Controlador que gestiona el proceso de login
 *
 * @param $request contenido de la petición http.
 * @param $response contenido de la response http.
 * @param $container parámetros de configuración de la aplicación
 */
function ctrlValidarLogin(Request $request, Response $response, Container $container) :Response
{
    $email = $request->get(INPUT_POST, "email");
    $password = $request->get(INPUT_POST, "password");

    $auth = new \App\Models\Auth($container->get("db"));
    $user = $auth->login($email, $password);

    if ($user) {
        // Guardar datos del usuario en la sesión
        $_SESSION['user'] = $user;
        $_SESSION['logat'] = true;
        
        $response->setSession("user", $user);
        $response->setSession("logat", true);
        
        $response->redirect("location: /index");
    } else {
        $_SESSION['logat'] = false;
        $_SESSION['user'] = null;
        
        $response->setSession("error", "Email o contraseña incorrectos");
        $response->setSession("logat", false);
        
        $response->redirect("location: /login");
    }

    return $response;
}
