<?php

namespace App\Controllers;  // Defining the namespace for the controller.

use Emeset\Contracts\Http\Request;  // Importing the Request contract for handling HTTP requests.
use Emeset\Contracts\Http\Response;  // Importing the Response contract for sending HTTP responses.
use Emeset\Contracts\Container;  // Importing the Container contract for dependency injection.

class ctrlEditUser
{
    // The editUser method handles the process of editing a user's details.
    public function editUser(Request $request, Response $response, Container $container)
    {
        // Retrieving the 'User' model from the container.
        $userdb = $container->get("User");

        // Capturing the form data sent via POST request.
        $id = $request->get(INPUT_POST, 'id');  // User ID
        $name = $request->get(INPUT_POST, 'name');  // User's name
        $surname = $request->get(INPUT_POST, 'surname');  // User's surname
        $email = $request->get(INPUT_POST, 'email');  // User's email
        $password = $request->get(INPUT_POST, 'password');  // User's password
        $role = $request->get(INPUT_POST, 'role');  // User's role
        $currentAvatar = $request->get(INPUT_POST, 'current_avatar');  // Current avatar path or filename

        // Handling the avatar file upload
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
            // If a new avatar is uploaded
            $avatar = $_FILES['avatar']['name'];  // The name of the uploaded file
            $uploadPath = __DIR__ . "/../../public/Images/" . $avatar;  // The directory where the avatar is saved

            // Move the uploaded file to the specified path
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                die("Error al subir el archivo.");  // Error message if file upload fails
            }
        } else {
            // If no new avatar is uploaded, use the current avatar
            $avatar = $currentAvatar;
        }

        // Calling the 'updateUser' method of the 'User' model to update the user's details in the database.
        $userdb->updateUser($id, $name, $surname, $email, $password, $role, $avatar);

        // Redirecting to the user management page after updating the user.
        $response->redirect("Location:/userManagement");
        return $response;
    }
}
