<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * ctrlWebcam: Controlador que carrega la pàgina de la webcam
 *
 * @param Request $request contingut de la petició http.
 * @param Response $response contingut de la resposta http.
 * @param Container $container paràmetres de configuració de l'aplicació
 *
 **/
function ctrlWebcam(Request $request, Response $response, Container $container) : Response
{
    $response->setTemplate("webcam.php");
    return $response;
} 