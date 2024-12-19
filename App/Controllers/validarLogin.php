<?php
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Controller that manages the login process
 *
 * @param $request Contains the HTTP request data.
 * @param $response Contains the HTTP response data.
 * @param $container Application configuration parameters.
 */
function ctrlValidarLogin(Request $request, Response $response, Container $container) :Response
{
    // Retrieve the email and password from the POST request
    $email = $request->get(INPUT_POST, "email");
    $password = $request->get(INPUT_POST, "password");

    // Create an instance of the Auth model to handle authentication
    $auth = new \App\Models\Auth($container->get("db"));

    // Attempt to login the user with the provided email and password
    $user = $auth->login($email, $password);

    if ($user) {
        // If login is successful, save user data in the session
        $_SESSION['user'] = $user;
        $_SESSION['logat'] = true;
        
        // Set session variables in the response for future requests
        $response->setSession("user", $user);
        $response->setSession("logat", true);
        
        // Redirect the user to the homepage after successful login
        $response->redirect("location: /index");
    } else {
        // If login fails, clear session data and show an error message
        $_SESSION['logat'] = false;
        $_SESSION['user'] = null;
        
        // Set an error message in the session to indicate invalid login credentials
        $response->setSession("error", "Incorrect email or password");
        $response->setSession("logat", false);
        
        // Redirect the user back to the login page
        $response->redirect("location: /login");
    }

    // Return the response object
    return $response;
}
