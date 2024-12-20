<?php

use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

/**
 * Middleware de autenticación
 */
function auth(Request $request, Response $response, Container $container, $next) {
    try {
        // Verificar si hay una sesión activa
        $user = $request->get("SESSION", "user");
        $logat = $request->get("SESSION", "logat");

        if (!isset($logat) || !$logat) {
            error_log("Auth Middleware: Usuario no autenticado");
            $response->setSession("error", "Por favor, inicie sesión para continuar.");
            header("Location: /login");
            exit();
        }

        // Si hay sesión, continuar
        $response->set("user", $user);
        $response->set("logat", $logat);

        return \Emeset\Middleware::next($request, $response, $container, $next);
        
    } catch (\Exception $e) {
        error_log("Error en auth middleware: " . $e->getMessage());
        $response->setSession("error", "Error de autenticación. Por favor, inicie sesión nuevamente.");
        header("Location: /login");
        exit();
    }
}

/**
 * Middleware que gestiona los roles
 */
function role($roles = []) {
    return function (Request $request, Response $response, Container $container, $next) use ($roles) {
        try {
            $user = $request->get("SESSION", "user");
            error_log("Role Middleware: Verificando rol de usuario - " . print_r($user, true));
            error_log("Roles permitidos: " . print_r($roles, true));
            
            // Verificar si el usuario existe y tiene un rol válido
            if (!$user || !isset($user['role']) || !in_array($user['role'], $roles)) {
                error_log("Role Middleware: Acceso denegado - rol no autorizado");
                
                // Guardar mensaje de error
                $mensaje = "No tiene permisos para acceder a esta página. Roles permitidos: " . implode(", ", $roles);
                $response->setSession("error", $mensaje);
                
                // Determinar la redirección según el rol
                $redirect = "/login"; // Por defecto
                
                if (isset($user['role'])) {
                    switch ($user['role']) {
                        case 'technician':
                            $redirect = "/usermachines";
                            break;
                        case 'supervisor':
                            $redirect = "/index";
                            break;
                        case 'administrator':
                            $redirect = "/adminPanel";
                            break;
                    }
                }
                
                error_log("Role Middleware: Redirigiendo a " . $redirect);
                header("Location: " . $redirect);
                exit();
            }

            return \Emeset\Middleware::next($request, $response, $container, $next);
            
        } catch (\Exception $e) {
            error_log("Error en role middleware: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            $response->setSession("error", "Error al verificar permisos: " . $e->getMessage());
            header("Location: /login");
            exit();
        }
    };
}

/**
 * Función auxiliar para detectar peticiones AJAX
 */
function isAjaxRequest() {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
           (!empty($_SERVER['HTTP_ACCEPT']) && 
            strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
}