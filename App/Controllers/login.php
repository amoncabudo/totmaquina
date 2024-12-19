<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Example login controller for the Emeset Framework
 * Sample framework for M07 Web Application Development.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Loads the login page.
 *
 **/

/**
 * ctrlLogin: Controller that loads the login page.
 *
 * @param $request HTTP request content.
 * @param $response HTTP response content.
 * @param array $config application configuration parameters.
 *
 **/
function ctrlLogin(Request $request, Response $response, Container $container) :Response
{
  // Counting how many times this page has been visited (error tracking)
  $error = $request->get("SESSION", "error");

  // Pass the error message to the view, if any.
  $response->set("error", $error);

  // Clear the session error after passing it to the view
  $response->setSession("error", "");

  // Set the template for the login page
  $response->SetTemplate("login.php");

  // Return the response with the login page template
  return $response;
}
