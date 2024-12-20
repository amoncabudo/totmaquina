<?php

namespace App\Controllers;

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class UserConfig {
    /**
     * Displays the user configuration page if the user is logged in.
     */
    public function index(Request $request, Response $response, Container $container) :Response
    {
        // Check if the user is logged in using the session variable
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            // If not logged in, redirect to the login page
            header("Location: /login");
            exit();
        }

        // Get user data from the session
        $user = $_SESSION["user"] ?? null;
        
        // If no user data in the session, redirect to the login page
        if (!$user) {
            header("Location: /login");
            exit();
        }

        // Set user data for use in the template
        $response->set("user", $user);
        // Load the user configuration template
        $response->SetTemplate("userConfig.php");
        
        // Return the response
        return $response;
    }

    /**
     * Handles updating the user's profile, including changing their username and password.
     */
    public function updateProfile(Request $request, Response $response, Container $container) :Response
    {
        // Check if the user is logged in using the session variable
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            // If not logged in, redirect to the login page
            header("Location: /login");
            exit();
        }

        // Get user data from the session
        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            // If no user data in the session, redirect to the login page
            header("Location: /login");
            exit();
        }

        // Get the new username and password data from the POST request
        $username = $request->get(INPUT_POST, "username");
        $currentPassword = $request->get(INPUT_POST, "currentPassword");
        $newPassword = $request->get(INPUT_POST, "newPassword");

        // Verify the current password by checking against the database
        $sql = "SELECT password FROM User WHERE id = :id";
        $stmt = $container->get("db")->prepare($sql);
        $stmt->execute(['id' => $user['id']]);
        $dbUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        // If the current password doesn't match, show an error message
        if (!password_verify($currentPassword, $dbUser['password'])) {
            $response->set("error", "The current password is incorrect");
            $response->set("user", $user);
            $response->SetTemplate("userConfig.php");
            return $response;
        }

        // Prepare the update SQL based on whether a new password is provided
        if ($newPassword) {
            // If a new password is provided, hash it and update both the name and password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE User SET name = :name, password = :password WHERE id = :id";
            $params = [
                'name' => $username,
                'password' => $hashedPassword,
                'id' => $user['id']
            ];
        } else {
            // If no new password is provided, only update the username
            $sql = "UPDATE User SET name = :name WHERE id = :id";
            $params = [
                'name' => $username,
                'id' => $user['id']
            ];
        }

        // Execute the update query
        $stmt = $container->get("db")->prepare($sql);
        $stmt->execute($params);

        // Update the session with the new username
        $user['name'] = $username;
        $_SESSION["user"] = $user;
        
        // Set a success message and load the user configuration template
        $response->set("success", "Profile updated successfully");
        $response->set("user", $user);
        $response->SetTemplate("userConfig.php");
        
        // Return the response
        return $response;
    }
}
