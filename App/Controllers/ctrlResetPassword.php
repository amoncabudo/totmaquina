<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ResetPassController
{   
    /**
     * Funcion que carga la vista de Restablecer contraseña
     *
     * @param \Emeset\Http\Request $request
     * @param \Emeset\Http\Response $response
     * @param \Emeset\Container $container
     * @return \Emeset\Http\Response
     */
    public function index($request, $response, $container)
    {
        $response->setTemplate("passwordRecovery.php");

        return $response;
    }

   /**
     * Funcion que envia un correo para restablecer la contraseña con un token de seguridad
     *
     * @param \Emeset\Http\Request $request
     * @param \Emeset\Http\Response $response
     * @param \Emeset\Container $container
     * @return \Emeset\Http\Response
     */

     public function reset($request, $response, $container)
     {
        $email = $request->get(INPUT_POST, "mail");

        $token = bin2hex(random_bytes(50));
        $tokenHash = hash_hmac("sha256", $token, "secret");
        $expiresAt = date("Y-m-d H:i:s", time() + 60 * 30);
        $user = $container->get("User");
        $user->updateResetToken($email, $tokenHash, $expiresAt);
        return $response;

        if($user){
            try{
                $this->sendMail($email, $token_hash);
            }catch(Exception $e){
                $response->setTemplate("MensajeErrorPass.php");
                return $response;
            }
        }
        $response->setTemplate("MensajeRecuperarPass.php");
        return $response;
     }

        /**
     * Funcion que carga la vista de Nueva contraseña con el token de seguridad
     *
     * @param \Emeset\Http\Request $request
     * @param \Emeset\Http\Response $response
     * @param \Emeset\Container $container
     * @return \Emeset\Http\Response
     */

     public function resetPassword($request, $response, $container){
        $token = $request->get(INPUT_GET, "token");
        $user = $container->get("User");
        $user->getUserByToken($token);
        if($user){
            $response->setTemplate("NuevaPassword.php");
            $response->set("token", $token);
        }else{
            $response->redirect("Location: /login");
        }
        return $response;
     }

         /**
     * Funcion que carga la vista de Nueva contraseña con el token de seguridad
     *
     * @param \Emeset\Http\Request $request
     * @param \Emeset\Http\Response $response
     * @param \Emeset\Container $container
     * @return \Emeset\Http\Response
     */

     public function updatePassword($request, $response, $container){
        $pass = $request->get(INPUT_POST, "contrasena");
        $pass2 = $request->get(INPUT_POST, "contrasena2");
        $token = $request->getParam("token");        
        $userM = $container->get("User");
        $user = $userM->getUserByToken($token);
        $email = $user["email"];
        if($pass === $pass2){
            $hash = password_hash($pass, PASSWORD_DEFAULT, ["cost" => 12]);
            $userM->updatePass($email, $hash);
            $response->redirect("Location: /login");
        }else{
            echo "Las contraseñas no coinciden";
        }   
        return $response;
     }
       /**
     * Funcion que envia un correo para restablecer la contraseña con un token de seguridad
     *
     * @param string $email
     * @param string $token_hash
     * @return void
     */
    public function sendMail($email, $token_hash){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPAuth = true;

        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->Username = "totmaquinas@gmail.com";
        $mail->Password = "totmaquinas";
        $mail->isHTML(true);
        $mail->setFrom("noreplatotmaquinas@gmail.com");
        $mail->addAddress($email);
        $mail->Subject = "Restablecer contraseña";
        $mail->Body = "Hola, para restablecer tu contraseña, haz click en el siguiente enlace: <a href='http://localhost:8080/reset-password?token=".$token_hash."'>Restablecer contraseña</a>";
        $mail->send();
    }
}