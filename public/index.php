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
include "../App/Controllers/maintenance_history.php";
include "../App/Middleware/auth.php";
include "../App/Middleware/test.php";
include "../App/Controllers/ctrlmachineinv.php";
include "../App/Controllers/ctrlindex.php";
include "../App/Controllers/maintenance.php";
include "../App/Controllers/maintenanceStats.php";
include "../App/Controllers/ctrlmachinedetail.php";
include "../App/Controllers/ctrluserManagement.php";
include "../App/Controllers/history.php";
include "../App/Controllers/ctrlAddUser.php";
include "../App/Controllers/NotificationsController.php";
include "../App/Controllers/ctrlAddMachine.php";
include "../App/Controllers/ctrlUserConfig.php";
include "../App/Controllers/ctrlEditUser.php";
include "../App/Controllers/ctrlDeleteUser.php";
include "../App/Controllers/ctrlUploadCSV.php";
include "../App/Controllers/ctrlEditMachine.php";
include "../App/Controllers/ctrladminPanel.php";
include "../App/Controllers/incidents.php";
include "../App/Controllers/ctrlmachines.php"; 
include "../App/Controllers/TestUserController.php";
include "../App/Controllers/ctrlgenerateqr.php";
include "../App/Controllers/ctrlMapMachine.php";


include "../App/Controllers/HistoryIncidentsController.php";
include "../App/Controllers/UserConfigController.php";

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
$app->route("privat", [\App\Controllers\Privat::class, "privat"], [[\App\Middleware\Auth::class, "auth"]]);
$app->route("tancar-sessio", "ctrlTancarSessio");
$app->route("index", "ctrlindex");

$app->route("maintenance", "maintenance");
// Ruta para mostrar las máquinas disponibles


// Maintenance routes
$app->route("maintenance", "maintenance");
$app->route("maintenance/create", "createMaintenance");
$app->route("maintenance/stats", "maintenanceStats");
$app->route("maintenance_history", [\App\Controllers\MaintenanceHistoryController::class, "index"]);

// API routes
$app->route("api/maintenance/history/{id}", function($request, $response) {
    $controller = new \App\Controllers\MaintenanceHistoryController();
    return $controller->getHistory($request, $response);
});

$app->route("api/machine/{id}", function($request, $response) {
    $controller = new \App\Controllers\MaintenanceHistoryController();
    return $controller->getMachineInfo($request, $response);
});

$app->route("api/search", [\App\Controllers\SearchController::class, "search"]);

// Machine routes
$app->route("machineinv", [\App\Controllers\getMachine::class, "ctrlmachineinv"]);
$app->route("/addmachine", [\App\Controllers\MachineController::class, "createMachine"]);
$app->route('machinedetail/{id}', [\App\Controllers\getMachinebyid::class, "ctrlMachineDetail"]);
$app->route("history", [\App\Controllers\HistoryIncidentsController::class, "index"]);
$app->route("history/incidents/{id}", [\App\Controllers\HistoryIncidentsController::class, "getHistory"]);
$app->route("/deletemachine/{id}", [\App\Controllers\ctrlDeleteMachine::class, "deleteMachine"]);
$app->post("/editmachine", [\App\Controllers\CtrlEditMachine::class, "editMachine"]);
$app->route("/uploadcsv", [\App\Controllers\UploadCSVController::class, "uploadCSV"]);
$app->get('/generate_machine_qr/{id}', [\App\Controllers\CtrlGenerateMachineQR::class, "generateQR"]);

$app->route("userManagement", [\App\Controllers\getUser::class, "ctrlUserManagement"]);
$app->route("adminPanel", [\App\Controllers\ctrladminPanel::class, "adminPanel"]);

// Rutas de notificaciones
$app->route("notifications", [\App\Controllers\NotificationsController::class, "index"]);
$app->post("notifications/delete/{id}", [\App\Controllers\NotificationsController::class, "delete"]);
$app->post("notifications/mark-as-read/{id}", [\App\Controllers\NotificationsController::class, "markAsRead"]);

$app->post("/addUser", [\App\Controllers\UserController::class, "createUser"]);
$app->post("/editUser", [\App\Controllers\editUser::class, "editUser"]);
$app->post("/deleteUser", [\App\Controllers\deleteUser::class, "deleteUser"]);

$app->route('machines', [\App\Controllers\incidents::class, 'incidents']);

$app->get('/incidents', 'incidents');
$app->post('/incidents/create', 'createIncident');
$app->post('/incidents/update-status', 'updateStatus');
$app->post('/incidents/assign-technician', 'assignTechnician');
$app->post('/incidents/delete', 'deleteIncident');
$app->get('/incidents/statistics', 'getStatistics');

// Rutas de configuración de usuario
$app->route("userconfig", [\App\Controllers\UserConfigController::class, "index"]);
$app->route("update-avatar", [\App\Controllers\UserConfigController::class, "updateAvatar"], [], "POST");
$app->route("update-profile", [\App\Controllers\UserConfigController::class, "updateProfile"], [], "POST");

$app->route("politica-cookies", function($request, $response) {
    $response->SetTemplate("politica-cookies.php");
    return $response;
});

$app->post("/createTestUser", [\App\Controllers\TestUserController::class, "createTestUser"]);

$app->route(Router::DEFAULT_ROUTE, "ctrlError");

$app->execute();



