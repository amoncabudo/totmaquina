<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request; // Importing the Request contract from the Emeset HTTP module
use \Emeset\Contracts\Http\Response; // Importing the Response contract from the Emeset HTTP module
use \Emeset\Contracts\Container; // Importing the Container contract for dependency injection

class UserController
{
    // Function to handle the creation of a new user
    function createUser($request, $response, $container)
    {
        // Retrieve form input data from the POST request
        $name = $request->get(INPUT_POST, 'name'); // Get the 'name' field from the form
        $surname = $request->get(INPUT_POST, 'surname'); // Get the 'surname' field from the form
        $email = $request->get(INPUT_POST, 'email'); // Get the 'email' field from the form
        $password = $request->get(INPUT_POST, 'password'); // Get the 'password' field from the form
        $role = $request->get(INPUT_POST, 'role'); // Get the 'role' field from the form

        // Initialize the avatar variable to null (in case no file is uploaded)
        $avatar = null;

        // Check if an avatar file is uploaded without errors
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $avatar = $_FILES['avatar']['name']; // Get the avatar file name
            // Move the uploaded avatar file to a specific directory
            move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . "/../../public/Images/" . $avatar);
        }

        // Get the "User" service from the container
        $userdb = $container->get("User");

        // Insert the user data into the database through the User service
        $result = $userdb->insertUser($name, $surname, $email, $password, $role, $avatar);

        // Redirect the response to the user management page
        $response->redirect("Location: /userManagement");

        return $response; // Return the response after the redirect
    }
}
