<?php
namespace App\Controllers;

class DashboardController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke($request, $response, $container) {
        // Render the dashboard view
        $response->set("title", "Dashboard");
        $response->setTemplate("dashboard.php");
        
        return $response;
    }
}
