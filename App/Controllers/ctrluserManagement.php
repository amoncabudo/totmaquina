<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrluserManagement
{
    /**
     * Retrieves all users from the database and displays them in the user management view.
     */
    function ctrlUserManagement($request, $response, $container)
    {
        // Get the User model from the container
        $userdb = $container->get("User");
        
        // Retrieve all users from the database using the User model's getAllUser() method
        $userdb = $userdb->getAllUser();
      
        // Set the retrieved users data for use in the template
        $response->set("users", $userdb);
        
        // Load the user management template to display the users
        $response->setTemplate('userManagement.php');
        
        // Return the response
        return $response;
    }
}
