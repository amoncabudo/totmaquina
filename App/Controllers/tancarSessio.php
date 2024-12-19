<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Controller that manages the logout process
 *
 * @param $request content of the HTTP request.
 * @param $response content of the HTTP response.
 * @param Container $container dependency container
 **/
function ctrlTancarSessio(Request $request, Response $response, Container $container) :Response
{
    // Start the session if it's not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Save the user ID before clearing the session
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null;

    // Clear specific session variables
    $response->setSession("logat", false); // Set logged-in status to false
    $response->setSession("user", null);   // Set user data to null

    // Clear the entire session array
    $_SESSION = array();

    // If a session cookie exists, delete it
    if (isset($_COOKIE[session_name()])) {
        // Set the session cookie with an expiration time in the past to remove it
        setcookie(session_name(), '', time()-42000, '/');
    }

    // Destroy the session
    session_destroy();

    // Perform the redirect to the login page before returning the response
    header("Location: /login");
    exit(); // Ensure no further code is executed after the redirect

    return $response;
}
