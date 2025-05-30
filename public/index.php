<?php

/**
 * Front controler
 * Exemple de MVC per a M07 Desenvolupament d'aplicacions web en entorn de servidor.
 * Aquest Framework implementa el mínim per tenir un MVC per fer pràctiques
 * de M07.
 * @author: Dani Prados dprados@cendrassos.net
 * @version 0.4.0
 *
 * Punt d'netrada de l'aplicació exemple del Framework Emeset.
 * Per provar com funciona es pot executer php -S localhost:8000 a la carpeta public.
 * I amb el navegador visitar la url http://localhost:8000/
 *
 */

use \Emeset\Contracts\Routers\Router;


error_reporting(E_ERROR | E_WARNING | E_PARSE);
include "../vendor/autoload.php";
include "../App/Controllers/portada.php";
include "../App/Controllers/error.php";
include "../App/Controllers/login.php";
include "../App/Controllers/validarLogin.php";
include "../App/Controllers/tancarSessio.php";
include "../App/Controllers/ctrlindex.php";
include "../App/Controllers/maintenance.php";
include "../App/Controllers/maintenanceStats.php";
include "../App/Controllers/history.php";
include "../App/Controllers/ctrlAddUser.php";
include "../App/Controllers/ctrlUserConfig.php";
include "../App/Controllers/ctrlUploadCSV.php";
include "../App/Controllers/incidents.php";
include "../App/Controllers/ctrlgenerateqr.php";
include "../App/Controllers/ctrlResetPassword.php";
include "../App/Controllers/HistoryIncidentsController.php";
// Middleware
include "../App/Middleware/supervisor.php";
include "../App/Middleware/technician.php";
include "../App/Middleware/administrator.php";
include "../App/Middleware/auth.php";
include "../App/Middleware/test.php";

/* Creem els diferents models */
$contenidor = new \App\Container(__DIR__ . "/../App/config.php");

$app = new \Emeset\Emeset($contenidor);
$app->middleware(function($request, $response, $container, $next) {
    return \App\Middleware\App::execute($request, $response, $container, $next);
});

// Routes
$app->route("", "ctrlPortada");
$app->route("login", "ctrlLogin");
$app->route("validar-login", "ctrlValidarLogin");
$app->route("tancar-sessio", "ctrlTancarSessio");
$app->route("index", "ctrlindex");

// Maintenance routes
$app->route("maintenance", [\App\Controllers\maintenance::class, "index"], [
    "auth", 
    role(['technician', 'administrator', 'supervisor'])
]);

$app->route("maintenance/create", function($request, $response) {
    $controller = new \App\Controllers\maintenance();
    return $controller->createMaintenance($request, $response);
}, [
    "auth", 
    role(['technician', 'administrator', 'supervisor'])
]);

$app->route("maintenance/stats", "maintenanceStats", [
    "auth", 
    role(['technician', 'administrator', 'supervisor'])
]);

$app->route("maintenance_history", function($request, $response) {
    $controller = new \App\Controllers\maintenance_history();
    return $controller->index($request, $response);
}, ["auth", role(['administrator', 'supervisor'])]);

// API routes
$app->route("api/maintenance/history/{id}", function($request, $response) {
    $controller = new \App\Controllers\maintenance_history();
    return $controller->getHistory($request, $response);
});

$app->route("api/machine/{id}", function($request, $response) {
    $controller = new \App\Controllers\maintenance_history();
    return $controller->getMachineInfo($request, $response);
});



// Ruta para la búsqueda de máquinas
$app->route("api/search", function($request, $response) {
    try {
        // Configuración inicial
        ini_set('display_errors', '1');
        error_reporting(0);
        if (ob_get_level()) ob_clean();

        header('Content-Type: application/json; charset=utf-8');

        // Obtener y validar el parámetro
        $query = trim($request->get(INPUT_GET, 'query') ?? '');
        if (strlen($query) < 2) {
            echo json_encode([
                'success' => false,
                'message' => "Ingrese al menos 2 caracteres para buscar",
                'results' => []
            ]);
            exit;
        }

        // Conexión a la base de datos
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $sql = $db->getConnection();

        // Obtener total de máquinas
        $testStmt = $sql->query("SELECT COUNT(*) as total FROM Machine");
        $totalMachines = $testStmt->fetch(\PDO::FETCH_ASSOC)['total'];

        // Ejecutar consulta
        $stmt = $sql->prepare("
            SELECT id, name, model, manufacturer, location 
            FROM Machine 
            WHERE LOWER(name) LIKE LOWER(:query) 
               OR LOWER(model) LIKE LOWER(:query) 
               OR LOWER(manufacturer) LIKE LOWER(:query) 
               OR LOWER(location) LIKE LOWER(:query)
            LIMIT 10
        ");
        $searchTerm = "%" . $query . "%";
        $stmt->execute(['query' => $searchTerm]);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'query' => $query,
            'total_machines' => $totalMachines,
            'results' => $results
        ]);
        exit;

    } catch (\PDOException $e) {
        // Error en base de datos
        error_log("Error de base de datos: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => "Error en la base de datos"
        ]);
        exit;

    } catch (\Exception $e) {
        // Error general
        error_log("Error general: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => "Error inesperado"
        ]);
        exit;
    }
});


// Ruta para el historial de incidencias
$app->route("history", "history", ["auth", role(['administrator', 'supervisor'])]);

// Ruta para obtener el historial de una máquina específica
$app->route("history/incidents/{id}", "getIncidentHistory", ["auth", role(['technician', 'administrator', 'supervisor'])]);

// Machine routes
$app->route("machineinv", [\App\Controllers\ctrlmachineinv::class, "ctrlmachineinv"]);
$app->route("/addmachine", [\App\Controllers\ctrlAddMachine::class, "createMachine"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->route('machinedetail/{id}', [\App\Controllers\ctrlmachinedetail::class, "ctrlMachineDetail"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->route("history", "history",["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->route("/deletemachine/{id}", [\App\Controllers\ctrlDeleteMachine::class, "deleteMachine"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->post("/editmachine", [\App\Controllers\CtrlEditMachine::class, "editMachine"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->route("/uploadcsv", [\App\Controllers\UploadCSVController::class, "uploadCSV"],["auth",
role(['administrator', 'supervisor'])]);


$app->get('/generate_machine_qr/{id}', [\App\Controllers\CtrlGenerateMachineQR::class, "generateQR"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->route("mapmachines", [\App\Controllers\ctrlMapMachine::class, "mapmachines"],["auth",
role(['technician', 'administrator', 'supervisor'])]);
$app->post('/update-machine-technicians/{id}', 'updateMachineTechnicians',["auth",
role(['technician', 'administrator', 'supervisor'])]);

$app->route("userManagement", [\App\Controllers\ctrluserManagement::class, "ctrlUserManagement"],["auth",
role(['administrator','supervisor'])]);
$app->route("history", "history",["auth",
role(['technician', 'administrator', 'supervisor'])]);


$app->route("adminPanel", [\App\Controllers\ctrladminPanel::class, "adminPanel"], ["auth", 
role(['administrator'])]);
// Rutas de notificaciones
$app->route("notifications", [\App\Controllers\NotificationsController::class, "index"], ["auth",
role(['technician','administrator','supervisor'])]);
$app->post("notifications/delete/{id}", [\App\Controllers\NotificationsController::class, "delete"],["auth",
role(['technician','administrator','supervisor'])]);
$app->post("notifications/mark-as-read/{id}", [\App\Controllers\NotificationsController::class, "markAsRead"],["auth",
role(['technician','administrator','supervisor'])]);

$app->post("/addUser", [\App\Controllers\UserController::class, "createUser"],["auth",
role(['administrator','supervisor'])]);

$app->post("/editUser", [\App\Controllers\ctrlEditUser::class, "editUser"],["auth",
role(['administrator','supervisor'])]);
$app->post("/deleteUser", [\App\Controllers\ctrlDeleteUser::class, "deleteUser"],["auth",
role(['administrator','supervisor'])]);

$app->route('machines', [\App\Controllers\incidents::class, 'incidents'],["auth",
role(['technician','administrator','supervisor'])]);

$app->route("usermachines", [\App\Controllers\usermachines::class, "index"], ["auth",
role(['technician', 'administrator', 'supervisor'])]);

$app->route("history", "history",["auth",
role(['administrator','supervisor'])]);
$app->get('/incidents', 'incidents',["auth",
role(['technician','administrator','supervisor'])]);
$app->post('/incidents/create', 'createIncident',["auth",
role(['technician','administrator','supervisor'])]);
$app->post('/incidents/update-status', 'updateStatus',["auth",
role(['technician','administrator','supervisor'])]);
$app->post('/incidents/assign-technician', 'assignTechnician',["auth",
role(['technician','administrator','supervisor'])]);
$app->post('/incidents/delete', 'deleteIncident',["auth",
role(['technician','administrator','supervisor'])]);
$app->get('/incidents/statistics', 'getStatistics',["auth",
role(['technician','administrator','supervisor'])]);

$app->post("user-machines/update-incident-status", [\App\Controllers\usermachines::class, "updateIncidentStatus"], ["auth",
role(['technician', 'administrator', 'supervisor'])]);

$app->post("user-machines/update-maintenance-status", [\App\Controllers\usermachines::class, "updateMaintenanceStatus"], ["auth",
role(['technician', 'administrator', 'supervisor'])]);

$app->route(Router::DEFAULT_ROUTE, "ctrlError");

$app->route("userconfig", [\App\Controllers\UserConfigController::class, "index"], ["auth",
role(['technician','administrator','supervisor'])]);

$app->post("update-avatar", [\App\Controllers\UserConfigController::class, "updateAvatar"], ["auth",
role(['technician','administrator','supervisor'])]);

$app->post("update-profile", [\App\Controllers\UserConfigController::class, "updateProfile"], ["auth",
role(['technician','administrator','supervisor'])]);

$app->route("politica-cookies", function($request, $response) {
    $response->SetTemplate("politica-cookies.php");
    return $response;
});

$app->route("passwordRecovery", [\App\Controllers\ResetPassController::class, "index"]);
$app->post("reset", [\App\Controllers\ResetPassController::class, "reset"]);
// Ruta GET para mostrar el formulario de nueva contraseña
$app->get("/NuevaPassword", [\App\Controllers\ResetPassController::class, "resetPassword"]);
$app->post("/NuevaPassword", [\App\Controllers\ResetPassController::class, "updatePassword"]);

$app->post("/createTestUser", [\App\Controllers\TestUserController::class, "createTestUser"],["auth",
role(['administrator'])]);
$app->get("/assigned-technicians", [\App\Controllers\MachinesController::class, "showAssignedTechnicians"], ["auth", 
role(['administrator', 'supervisor'])]);
$app->post("/api/change-technician", [\App\Controllers\MachinesController::class, "changeTechnician"], ["auth", 
role(['administrator', 'supervisor'])]);

$app->get("maintenance-history", function($request, $response) {
    $controller = new \App\Controllers\maintenance_history();
    return $controller->index($request, $response);
}, ["auth", role(['technician', 'administrator', 'supervisor'])]);

$app->get("maintenance-history/get/{id}", function($request, $response) {
    $controller = new \App\Controllers\maintenance_history();
    $controller->getHistory($request, $response);
    exit; // Importante: asegurarnos de que no hay más salida después
}, ["auth", role(['technician', 'administrator', 'supervisor'])]);

// Rutas para asignación de técnicos
$app->post("maintenance/assign-technician", function($request, $response) {
    $controller = new \App\Controllers\maintenance();
    return $controller->assignTechnician($request, $response);
}, ["auth", role(['administrator', 'supervisor'])]);

$app->post("maintenance/remove-technician", function($request, $response) {
    $controller = new \App\Controllers\maintenance();
    return $controller->removeTechnician($request, $response);
}, ["auth", role(['administrator', 'supervisor'])]);

$app->execute();
