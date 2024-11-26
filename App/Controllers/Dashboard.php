<?php

namespace App\Controllers;

class Dashboard
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $container)
    {
        // Aquí puedes agregar la lógica necesaria
        
        $response->setTemplate("dashboard.php");
        return $response;
    }
} 