<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Controlador que gestiona el cierre de sesión
 *
 * @param $request contingut de la peticó http.
 * @param $response contingut de la response http.
 * @param Container $container contenedor de dependencias
 **/
function ctrlTancarSessio(Request $request, Response $response, Container $container) :Response
{
    // Iniciamos la sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Guardamos el ID del usuario antes de limpiar la sesión
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

    // Limpiamos las variables de sesión específicas
    $response->setSession("logat", false);
    $response->setSession("user", null);

    // Limpiamos el array de sesión
    $_SESSION = array();

    // Eliminamos la cookie de sesión si existe
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    // Destruimos la sesión
    session_destroy();

    // Hacemos el redirect antes de devolver la respuesta
    header("Location: /login");
    exit();

    return $response;
}
