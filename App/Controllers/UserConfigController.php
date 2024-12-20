<?php

namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class UserConfigController {
    // Displays the user configuration page if the user is logged in
    public function index(Request $request, Response $response, Container $container) {
        // Check if the user is logged in
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            $response->redirect("Location: /login");  // Redirect to login if not logged in
            return $response;
        }

        $user = $_SESSION["user"] ?? null;  // Get user data from session
        if (!$user) {
            $response->redirect("Location: /login");  // Redirect to login if user data is not found
            return $response;
        }

        // Set the user data in the response
        $response->set("user", $user);
        $response->SetTemplate("userConfig.php");  // Set the template for the user config page
        return $response;
    }

    // Handles the avatar update for the user
    public function updateAvatar(Request $request, Response $response, Container $container) {
        // Disable PHP error output
        ini_set('display_errors', 0);
        error_reporting(0);
        
        // Ensure no previous output exists
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Set the Content-Type header for JSON response
        header('Content-Type: application/json; charset=utf-8');

        try {
            // Check if an avatar image is uploaded
            if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
                echo json_encode([  // Return error if no file is selected
                    'success' => false,
                    'error' => "No file selected"
                ]);
                exit();
            }

            // Get file details
            $file = $_FILES['avatar'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // Validate the file type (only images are allowed)
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                echo json_encode([  // Return error if file type is not allowed
                    'success' => false,
                    'error' => "Invalid file type. Only JPG, PNG, and GIF images are allowed."
                ]);
                exit();
            }

            // Check for file upload errors
            if ($fileError !== 0) {
                echo json_encode([  // Return error if there was an upload error
                    'success' => false,
                    'error' => "Error uploading file."
                ]);
                exit();
            }

            // Check the file size (max 2MB)
            if ($fileSize > 2097152) {
                echo json_encode([  // Return error if the file is too large
                    'success' => false,
                    'error' => "File too large. Maximum size is 2MB."
                ]);
                exit();
            }

            // Ensure the image directory exists and is writable
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Images/';
            if (!file_exists($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    throw new \Exception("Could not create the images directory.");
                }
            }

            if (!is_writable($uploadDir)) {
                throw new \Exception("The images directory is not writable.");
            }

            // Generate a unique name for the uploaded file
            $newFileName = uniqid('avatar_') . '.' . $fileExt;
            $uploadPath = $uploadDir . $newFileName;

            // Remove the previous avatar if exists
            if (isset($_SESSION['user']['avatar']) && !empty($_SESSION['user']['avatar'])) {
                $oldAvatarPath = $uploadDir . $_SESSION['user']['avatar'];
                if (file_exists($oldAvatarPath)) {
                    @unlink($oldAvatarPath);  // Delete the old avatar
                }
            }

            // Move the uploaded file to the images directory
            if (!move_uploaded_file($fileTmpName, $uploadPath)) {
                throw new \Exception("Error moving the uploaded file.");
            }

            // Update the avatar in the database
            try {
                $userId = $_SESSION['user']['id'];
                $db = $container->get("db");
                $stmt = $db->prepare("UPDATE User SET avatar = ? WHERE id = ?");
                $stmt->execute([$newFileName, $userId]);

                // Update the avatar in the session
                $_SESSION['user']['avatar'] = $newFileName;
                
                echo json_encode([  // Return success message with the new avatar
                    'success' => true,
                    'avatar' => $newFileName,
                    'message' => "Profile picture updated successfully."
                ]);
                exit();

            } catch (\PDOException $e) {
                // If there's a database error, delete the uploaded file
                if (file_exists($uploadPath)) {
                    @unlink($uploadPath);
                }
                throw new \Exception("Database update error: " . $e->getMessage());
            }

        } catch (\Exception $e) {
            error_log("Error in updateAvatar: " . $e->getMessage());
            
            // If there's an error, delete the uploaded file if it exists
            if (isset($uploadPath) && file_exists($uploadPath)) {
                @unlink($uploadPath);
            }
            
            echo json_encode([  // Return error message
                'success' => false,
                'error' => "Error processing request: " . $e->getMessage()
            ]);
            exit();
        }
    }

    // Handles the user profile update
    public function updateProfile(Request $request, Response $response, Container $container) {
        // Check if the user is logged in
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            $response->redirect("Location: /login");  // Redirect to login if not logged in
            return $response;
        }

        $user = $_SESSION["user"] ?? null;  // Get user data from session
        if (!$user) {
            $response->redirect("Location: /login");  // Redirect to login if user data is not found
            return $response;
        }

        // Get the new user profile data from the request
        $name = $request->get(INPUT_POST, "name");
        $currentPassword = $request->get(INPUT_POST, "current_password");
        $newPassword = $request->get(INPUT_POST, "new_password");
        $confirmPassword = $request->get(INPUT_POST, "confirm_password");

        // Validate mandatory fields
        if (empty($name) || empty($currentPassword)) {
            $_SESSION['error'] = "Name and current password are required.";
            $response->redirect("Location: /userconfig");  // Redirect back if validation fails
            return $response;
        }

        // Check if the current password matches the one in the database
        $db = $container->get("db");
        $stmt = $db->prepare("SELECT password FROM User WHERE id = ?");
        $stmt->execute([$user['id']]);
        $dbUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $dbUser['password'])) {
            $_SESSION['error'] = "Current password is incorrect.";
            $response->redirect("Location: /userconfig");  // Redirect back if the password is incorrect
            return $response;
        }

        // If a new password is provided, validate it
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Passwords do not match.";
                $response->redirect("Location: /userconfig");  // Redirect back if passwords don't match
                return $response;
            }
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "New password must be at least 6 characters long.";
                $response->redirect("Location: /userconfig");  // Redirect back if the password is too short
                return $response;
            }
            
            // Update the name and password in the database
            $sql = "UPDATE User SET name = ?, password = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, password_hash($newPassword, PASSWORD_DEFAULT), $user['id']]);
        } else {
            // If no new password is provided, just update the name
            $sql = "UPDATE User SET name = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $user['id']]);
        }

        // Update the user data in the session
        $_SESSION['user']['name'] = $name;
        $_SESSION['success'] = "Profile updated successfully.";
        
        $response->redirect("Location: /userconfig");  // Redirect back to user config page
        return $response;
    }
}
