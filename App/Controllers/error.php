<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Error page controller for the Emeset framework
 * Example framework for M07 Web Application Development.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Loads the homepage
 *
 **/

/**
 * ctrlError: Controller that loads the error page
 *
 * @param $request contents of the HTTP request.
 * @param $response contents of the HTTP response.
 * @param $container application configuration parameters
 *
 **/
function ctrlError(Request $request, Response $response, Container $container) :Response
{

  $error = $request->get("SESSION", "error");
  $response->set("error", $error);
  $response->SetTemplate("error.php");

  return $response;
}
