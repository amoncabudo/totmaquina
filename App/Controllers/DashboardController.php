<?php
namespace App\Controllers;

class DashboardController {

    private $container;

    public function __construct($container) {
        $this->container = $container;
    }

    public function __invoke($request, $response, $container) {
        // Renderizar la vista del dashboard
        $response->set("title", "Dashboard");
        $response->setTemplate("dashboard.php");
        
        return $response;
    }
} 