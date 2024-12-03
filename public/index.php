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
include "../App/Middleware/auth.php";
include "../App/Middleware/test.php";
include "../App/Controllers/ctrlmachineinv.php";
include "../App/Controllers/ctrlindex.php";
include "../App/Controllers/maintenance.php";
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
include "../App/Controllers/ctrlgenerateqr.php";


/* Creem els diferents models */
$contenidor = new \App\Container(__DIR__ . "/../App/config.php");

$app = new \Emeset\Emeset($contenidor);
$app->middleware(function($request, $response, $container, $next) {
    return \App\Middleware\App::execute($request, $response, $container, $next);
});

$app->route("", "ctrlPortada");
$app->route("login", "ctrlLogin");
$app->route("validar-login", "ctrlValidarLogin");
$app->route("privat", [\App\Controllers\Privat::class, "privat"], [[\App\Middleware\Auth::class, "auth"]]);
$app->route("tancar-sessio", "ctrlTancarSessio");
$app->route("index", "ctrlindex");
$app->route("maintenance", "maintenance");

$app->route("machineinv", [\App\Controllers\getMachine::class, "ctrlmachineinv"]);
$app->route("/addmachine", [\App\Controllers\MachineController::class, "createMachine"]);
$app->route('machinedetail/{id}', [\App\Controllers\getMachinebyid::class, "ctrlMachineDetail"]);
$app->route("history", "history");
$app->route("/deletemachine/{id}", [\App\Controllers\ctrlDeleteMachine::class, "deleteMachine"]);
$app->post("/editmachine", [\App\Controllers\CtrlEditMachine::class, "editMachine"]);
$app->route("/uploadcsv", [\App\Controllers\UploadCSVController::class, "uploadCSV"]);


$app->route("userManagement", [\App\Controllers\getUser::class, "ctrlUserManagement"]);
$app->route("history", "history");

$app->route("adminPanel", [\App\Controllers\ctrladminPanel::class, "adminPanel"]);
// Rutas de notificaciones
$app->route("notifications", [\App\Controllers\NotificationsController::class, "index"]);
$app->post("notifications/delete/{id}", [\App\Controllers\NotificationsController::class, "delete"]);
$app->post("notifications/mark-as-read/{id}", [\App\Controllers\NotificationsController::class, "markAsRead"]);

$app->post("/addUser", [\App\Controllers\UserController::class, "createUser"]);

$app->post("/editUser", [\App\Controllers\editUser::class, "editUser"]);
$app->post("/deleteUser", [\App\Controllers\deleteUser::class, "deleteUser"]);


$app->route("history", "history");
$app->get('/incidents', 'incidents');
$app->post('/incidents/create', 'createIncident');
$app->post('/incidents/update-status', 'updateStatus');
$app->post('/incidents/assign-technician', 'assignTechnician');
$app->post('/incidents/delete', 'deleteIncident');
$app->get('/incidents/statistics', 'getStatistics');
$app->route("ajax", function ($request, $response) {
    $response->set("result", "ok");
    return $response;
});

$app->route("/hola/{id}", function ($request, $response) {
    $id = $request->getParam("id");
    $response->setBody("Hola {$id}!");
    return $response;
});

$app->route(Router::DEFAULT_ROUTE, "ctrlError");

$app->route("userconfig", [\App\Controllers\UserConfig::class, "index"]);
$app->route("politica-cookies", function($request, $response) {
    $response->SetTemplate("politica-cookies.php");
    return $response;
});

$app->post("update-profile", [\App\Controllers\UserConfig::class, "updateProfile"]);

$app->execute();


$app->execute();
