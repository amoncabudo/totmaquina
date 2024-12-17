<?php

namespace App;

use Emeset\Container as EmesetContainer;

class Container extends EmesetContainer {

    public function __construct($config){
        parent::__construct($config);
        
        $this["\App\Controllers\Privat"] = function ($c) {
            return new \App\Controllers\Privat($c);
        };

        // Configuración de la base de datos principal
        $this["db"] = function ($c) {
            $config = $c->get("config")["db"];
            try {
                $connexio = new \PDO(
                    "mysql:host=" . $config["host"] . ";dbname=" . $config["db"],
                    $config["user"],
                    $config["pass"],
                    array(
                        \PDO::ATTR_PERSISTENT => true,
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                    )
                );
            } catch (\PDOException $e) {
                throw new \Exception("Error connecting to the database: " . $e->getMessage());
            }
            return $connexio;
        };

        $this["Db"] = function ($c) {
            $config = $c->get("config");
            $db = new \App\Models\Db($config["db"]["user"],
            $config["db"]["pass"],
            $config["db"]["db"], 
            $config["db"]["host"]);
            return $db;
        };

        $this["User"] = function ($c) {
            $db = $c->get("Db");
            $task = new \App\Models\User($db->getConnection());
            return $task;
        };

        $this["Machine"] = function ($c) {
            $db = $c->get("Db");
            $task = new \App\Models\Machine($db->getConnection());
            return $task;
        };

        // Registro del modelo Incident
        $this["Incident"] = function ($c) {
            $db = $c->get("Db");
            return new \App\Models\Incident($db->getConnection());
        };

        // Registro del modelo Maintenance
        $this["Maintenance"] = function ($c) {
            $db = $c->get("Db");
            return new \App\Models\Maintenance($db->getConnection());
        };
    }
}
