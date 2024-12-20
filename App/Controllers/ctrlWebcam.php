<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * ctrlWebcam: Controller that loads the webcam page
 *
 * @param Request $request Content of the HTTP request.
 * @param Response $response Content of the HTTP response.
 * @param Container $container Application configuration parameters
 *
 **/
function ctrlWebcam(Request $request, Response $response, Container $container) : Response
{
    // Set the template for the webcam page
    $response->setTemplate("webcam.php");
    
    // Return the response to be rendered
    return $response;
}
