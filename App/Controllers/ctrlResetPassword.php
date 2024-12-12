<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ResetPassController
{
    /**
     * Carga la vista de Restablecer contraseña
     */
    public function index($request, $response, $container)
    {
        $response->setTemplate("passwordRecovery.php");
        return $response;
    }

    /**
     * Envía un correo para restablecer la contraseña con un token de seguridad
     */
    public function reset($request, $response, $container)
    {
        $email = $request->get(INPUT_POST, "email");

        // Generar token y hash
        $token = bin2hex(random_bytes(32)); 
        $tokenHash = hash('sha256', $token); 
        $expiresAt = date("Y-m-d H:i:s", time() + 60 * 30);

        $user = $container->get("User");
        if ($user->updateResetToken($email, $tokenHash, $expiresAt)) {
            try {
                $this->sendMail($email, $tokenHash);
                $response->setTemplate("MensajeRecuperarPass.php");
            } catch (\Exception $e) {
                error_log("Error al enviar correo: " . $e->getMessage());
                $response->setTemplate("MensajeErrorPass.php");
            }
        } else {
            $response->setTemplate("MensajeErrorPass.php");
        }

        return $response;
    }

    /**
     * Carga la vista de Nueva contraseña con el token de seguridad
     */
    public function resetPassword($request, $response, $container)
    {
        try {
            $token = $request->get(INPUT_GET, "token");
            if (empty($token)) {
                error_log("Token vacío en resetPassword");
                $response->redirect("/login");
                return $response;
            }

            $user = $container->get("User")->getUserByToken($token);
            error_log("Usuario encontrado: " . ($user ? 'sí' : 'no'));

            if (!$user) {
                error_log("Usuario no encontrado para el token");
                header("Location: /login");
                exit();
            }

            // Verificar si el token ha expirado
            $expiresAt = strtotime($user['reset_token_expires_at']);
            if (time() > $expiresAt) {
                error_log("Token expirado");
                $response->redirect("/login");
                return $response;
            }

            $response->setTemplate("NuevaPassword.php");
            $response->set("token", $token);
            
            return $response;
        } catch (\Exception $e) {
            error_log("Error en resetPassword: " . $e->getMessage());
            $response->redirect("/login");
            return $response;
        }
    }        

    /**
     * Actualiza la contraseña del usuario
     */
    public function updatePassword($request, $response, $container)
    {
        $password = $request->get(INPUT_POST, "password");
        $password2 = $request->get(INPUT_POST, "password2");
        $token = $request->get(INPUT_POST, "token");

        
        // Depuración
        // var_dump($password, $password2, $token);
    
        if ($password !== $password2) {
            $response->setTemplate("MensajeErrorPass.php");
            return $response;
        }
    
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = $container->get("User");    
        $updated = $user->updatePassword($token, $password);

        if (!$updated) {
            $response->setTemplate("MensajeErrorPass.php");
            return $response;
        }
    
        header("Location: /login");
        exit();
    }

    /**
     * Envía un correo con un enlace para restablecer la contraseña
     */
    public function sendMail($email, $token)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'pbosch@cendrassos.net'; 
            $mail->Password = 'lamborgini04062003';          
            $mail->Port = 587;

            // Configuración del correo
            $mail->setFrom('noreplytotmaquinas@gmail.com', 'Soporte ToT Máquinas');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperar contraseña';
            $mail->Body = "Haz clic en este enlace para recuperar tu contraseña: 
           <a href='http://localhost/NuevaPassword?token=$token'>Recuperar contraseña</a>";

            

            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Error al enviar el correo: " . $e->getMessage());
        }
    }
}
