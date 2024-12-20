<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Front page controller of the Emeset Framework example
 * Example framework for M07 Web Application Development.
 * @author: Dani Prados dprados@cendrassos.net
 *
 * Loads the front page
 *
 **/

/**
 * ctrlPortada: Controller that loads the front page
 *
 * @param $request the content of the HTTP request.
 * @param $response the content of the HTTP response.
 * @param array $config the configuration parameters of the application
 *
 **/
function ctrlPortada(Request $request, Response $response, Container $container) :Response
{
    // Get user data from the container
    $user = $container->get("User");
    $user = $user->getAllUser(); 

    // Count how many times the user has visited this page
    $visites = $request->get(INPUT_COOKIE, "visites");
    if (!is_null($visites)) {
        $visites = (int)$visites + 1;
    } else {
        $visites = 1;
    }

    // Set a cookie to track the number of visits for one month
    $response->setcookie("visites", $visites, strtotime("+1 month"));

    // Create a message based on the number of visits
    $missatge = "";
    if ($visites == 1) {
        $missatge = "Welcome! This is the first page you've visited on this website!";
    } else {
        $missatge = "Hello! You've visited {$visites} pages on this website!";
    }

    // Set the user data and message for the response
    $response->set("users", $user);
    $response->set("missatge", $missatge);

    // Set the template to be used for rendering the response
    $response->SetTemplate("index.php");

    return $response;
}
