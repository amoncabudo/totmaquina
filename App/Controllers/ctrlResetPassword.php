<?php
namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ResetPassController
{
    /**
     * Loads the password recovery view.
     */
    public function index($request, $response, $container)
    {
        $response->setTemplate("passwordRecovery.php");
        return $response;
    }

    /**
     * Sends an email to reset the password with a security token.
     */
    public function reset($request, $response, $container)
    {
        $email = $request->get(INPUT_POST, "email");

        // Generate token and hash
        $token = bin2hex(random_bytes(32)); // Generates a random 32-byte token
        $tokenHash = hash('sha256', $token); // Hash the token using SHA-256
        $expiresAt = date("Y-m-d H:i:s", time() + 60 * 30); // Set expiration time to 30 minutes from now

        // Get the user object from the container
        $user = $container->get("User");

        // Update the reset token in the database
        if ($user->updateResetToken($email, $tokenHash, $expiresAt)) {
            try {
                // Send the email with the reset link
                $this->sendMail($email, $tokenHash);
                $response->setTemplate("MensajeRecuperarPass.php");
            } catch (\Exception $e) {
                error_log("Error sending email: " . $e->getMessage());
                $response->setTemplate("MensajeErrorPass.php");
            }
        } else {
            $response->setTemplate("MensajeErrorPass.php");
        }

        return $response;
    }

    /**
     * Loads the new password view with the security token.
     */
    public function resetPassword($request, $response, $container)
    {
        try {
            // Retrieve the token from the GET request
            $token = $request->get(INPUT_GET, "token");

            // Check if the token is empty
            if (empty($token)) {
                error_log("Empty token in resetPassword");
                $response->redirect("/login");
                return $response;
            }

            // Get the user associated with the token
            $user = $container->get("User")->getUserByToken($token);
            error_log("User found: " . ($user ? 'yes' : 'no'));

            // If the user is not found, redirect to login
            if (!$user) {
                error_log("User not found for the token");
                header("Location: /login");
                exit();
            }

            // Check if the token has expired
            $expiresAt = strtotime($user['reset_token_expires_at']);
            if (time() > $expiresAt) {
                error_log("Token expired");
                $response->redirect("/login");
                return $response;
            }

            // Set the template for the new password view
            $response->setTemplate("NuevaPassword.php");
            $response->set("token", $token);

            return $response;
        } catch (\Exception $e) {
            error_log("Error in resetPassword: " . $e->getMessage());
            $response->redirect("/login");
            return $response;
        }
    }

    /**
     * Updates the user's password.
     */
    public function updatePassword($request, $response, $container)
    {
        $password = $request->get(INPUT_POST, "password");
        $password2 = $request->get(INPUT_POST, "password2");
        $token = $request->get(INPUT_POST, "token");

        // Debugging: Check the password and token values
        // var_dump($password, $password2, $token);

        // Check if the passwords match
        if ($password !== $password2) {
            $response->setTemplate("MensajeErrorPass.php");
            return $response;
        }

        // Update the user's password in the database
        $user = $container->get("User");
        $updated = $user->updatePassword($token, $password);

        // If the update fails, show an error message
        if (!$updated) {
            $response->setTemplate("MensajeErrorPass.php");
            return $response;
        }

        // Redirect to login page after successful password change
        header("Location: /login");
        exit();
    }

    /**
     * Sends an email with a reset password link.
     */
    public function sendMail($email, $token)
    {
        $mail = new PHPMailer(true);

        try {
            // Set up the mail server
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pbosch@cendrassos.net';
            $mail->Password = 'lamborgini04062003'; // Your SMTP password (should be stored securely)
            $mail->Port = 587;

            // Set up the email content
            $mail->setFrom('noreplytotmaquinas@gmail.com', 'Soporte ToT Máquinas');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperar contraseña';
            $mail->Body = "Click this link to reset your password: 
           <a href='http://localhost/NuevaPassword?token=$token'>Recuperar contraseña</a>";

            // Send the email
            $mail->send();
        } catch (Exception $e) {
            // Log any errors
            throw new Exception("Error sending email: " . $e->getMessage());
        }
    }
}
