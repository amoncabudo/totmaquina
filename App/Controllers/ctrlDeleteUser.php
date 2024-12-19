<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use \Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use \Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class ctrlDeleteUser
{
    // Function to delete a user based on the provided user ID
    function deleteUser(Request $request, Response $response, Container $container)
    {
        // Retrieving the user ID from the POST request
        $id = $request->get(INPUT_POST, 'id');
        
        // Retrieving the user model from the container
        $userdb = $container->get("User");

        // Deleting the user by calling the deleteUser method from the User model
        $userdb->deleteUser($id);

        // Redirecting to the user management page after successful deletion
        $response->redirect("Location: /userManagement");

        // Returning the response object
        return $response;
    }
}
