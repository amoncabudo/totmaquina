<?php

namespace App\Controllers;

use Emeset\Contracts\Http\Request;
use Emeset\Contracts\Http\Response;
use Emeset\Contracts\Container;

class UserConfigController {
    public function index(Request $request, Response $response, Container $container) {
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            $response->redirect("Location: /login");
            return $response;
        }

        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            $response->redirect("Location: /login");
            return $response;
        }

        $response->set("user", $user);
        $response->SetTemplate("userConfig.php");
        return $response;
    }

    public function updateAvatar(Request $request, Response $response, Container $container) {
        // Asegurarnos de que no se ha enviado ningún output antes
        if (headers_sent()) {
            http_response_code(500);
            die(json_encode(['success' => false, 'error' => 'Headers already sent']));
        }

        // Establecer headers para JSON
        header('Content-Type: application/json');

        try {
            if (!isset($_FILES['avatar'])) {
                http_response_code(400);
                die(json_encode([
                    'success' => false,
                    'error' => "No se ha seleccionado ninguna imagen"
                ]));
            }

            $file = $_FILES['avatar'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // Validar el archivo
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowed)) {
                http_response_code(400);
                die(json_encode([
                    'success' => false,
                    'error' => "Tipo de archivo no permitido. Solo se permiten imágenes JPG, PNG y GIF."
                ]));
            }

            if ($fileError !== 0) {
                http_response_code(400);
                die(json_encode([
                    'success' => false,
                    'error' => "Hubo un error al subir el archivo."
                ]));
            }

            if ($fileSize > 2097152) { // 2MB en bytes
                http_response_code(400);
                die(json_encode([
                    'success' => false,
                    'error' => "El archivo es demasiado grande. Máximo 2MB."
                ]));
            }

            // Usar la carpeta Images existente
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Images/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Generar nombre único para el archivo
            $newFileName = uniqid('avatar_') . '.' . $fileExt;
            $uploadPath = $uploadDir . $newFileName;

            // Eliminar avatar anterior si existe
            if (isset($_SESSION['user']['avatar']) && !empty($_SESSION['user']['avatar'])) {
                $oldAvatarPath = $uploadDir . $_SESSION['user']['avatar'];
                if (file_exists($oldAvatarPath)) {
                    unlink($oldAvatarPath);
                }
            }

            // Mover el archivo
            if (!move_uploaded_file($fileTmpName, $uploadPath)) {
                http_response_code(500);
                die(json_encode([
                    'success' => false,
                    'error' => "Error al guardar el archivo."
                ]));
            }

            // Actualizar en la base de datos
            try {
                $userId = $_SESSION['user']['id'];
                $db = $container->get("db");
                $stmt = $db->prepare("UPDATE User SET avatar = ? WHERE id = ?");
                $stmt->execute([$newFileName, $userId]);

                // Actualizar la sesión
                $_SESSION['user']['avatar'] = $newFileName;
                
                die(json_encode([
                    'success' => true,
                    'avatar' => $newFileName,
                    'message' => "Foto de perfil actualizada correctamente."
                ]));
            } catch (\PDOException $e) {
                // Si hay error en la BD, eliminar el archivo subido
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
                http_response_code(500);
                die(json_encode([
                    'success' => false,
                    'error' => "Error al actualizar la base de datos: " . $e->getMessage()
                ]));
            }

        } catch (\Exception $e) {
            http_response_code(500);
            die(json_encode([
                'success' => false,
                'error' => "Error al procesar la solicitud: " . $e->getMessage()
            ]));
        }
    }

    public function updateProfile(Request $request, Response $response, Container $container) {
        if (!isset($_SESSION["logat"]) || !$_SESSION["logat"]) {
            $response->redirect("Location: /login");
            return $response;
        }

        $user = $_SESSION["user"] ?? null;
        if (!$user) {
            $response->redirect("Location: /login");
            return $response;
        }

        $name = $request->get(INPUT_POST, "name");
        $currentPassword = $request->get(INPUT_POST, "current_password");
        $newPassword = $request->get(INPUT_POST, "new_password");
        $confirmPassword = $request->get(INPUT_POST, "confirm_password");

        // Validar campos obligatorios
        if (empty($name) || empty($currentPassword)) {
            $_SESSION['error'] = "El nombre y la contraseña actual son obligatorios.";
            $response->redirect("Location: /userconfig");
            return $response;
        }

        // Verificar contraseña actual
        $db = $container->get("db");
        $stmt = $db->prepare("SELECT password FROM User WHERE id = ?");
        $stmt->execute([$user['id']]);
        $dbUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!password_verify($currentPassword, $dbUser['password'])) {
            $_SESSION['error'] = "La contraseña actual no es correcta.";
            $response->redirect("Location: /userconfig");
            return $response;
        }

        // Si se proporciona nueva contraseña
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "Las contraseñas no coinciden.";
                $response->redirect("Location: /userconfig");
                return $response;
            }
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = "La nueva contraseña debe tener al menos 6 caracteres.";
                $response->redirect("Location: /userconfig");
                return $response;
            }
            
            $sql = "UPDATE User SET name = ?, password = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, password_hash($newPassword, PASSWORD_DEFAULT), $user['id']]);
        } else {
            $sql = "UPDATE User SET name = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$name, $user['id']]);
        }

        $_SESSION['user']['name'] = $name;
        $_SESSION['success'] = "Perfil actualizado correctamente.";
        
        $response->redirect("Location: /userconfig");
        return $response;
    }
} 