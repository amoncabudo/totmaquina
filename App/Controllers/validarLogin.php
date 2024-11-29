<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Controlador que gestiona el procés de login
 * Framework d'exemple per a M07 Desenvolupament d'aplicacions web.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Comprova si l'usuari s'ha autentificat correctament
 *
 **/

/**
 * ctrlValidarLogin: Controlador que comprova si l'usuari s'ha autentificat
 * correctament
 *
 * @param $request contingut de la peticó http.
 * @param $response contingut de la response http.
 * @param $container  paràmetres de configuració de l'aplicació
 *
 **/
function ctrlValidarLogin(Request $request, Response $response, Container $container) :Response
{
    $email = $request->get(INPUT_POST, "email");
    $password = $request->get(INPUT_POST, "password");

    $auth = new \App\Models\Auth($container->get("db"));
    $user = $auth->login($email, $password);

    if ($user) {
        // Guardar datos del usuario en la sesión
        $response->setSession("user", $user);
        $response->setSession("logat", true);
        header("Location: /index");
        exit();
    } else {
        $response->setSession("error", "Email o contraseña incorrectos");
        $response->setSession("logat", false);
        header("Location: /login");
        exit();
    }

    return $response;
}
