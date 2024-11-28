<?php

namespace App;

use Emeset\Container as EmesetContainer;

class Container extends EmesetContainer {

    public function __construct($config){
        parent::__construct($config);
        
        $this["\App\Controllers\Privat"] = function ($c) {
            return new \App\Controllers\Privat($c);
        };

        $this["Db"] = function ($c) {
            $config =  $c->get("config");
            $db = new \App\Models\Db($config["db"]["user"],
            $config["db"]["pass"],
            $config["db"]["db"], 
            $config["db"]["host"]);
            return $db;
        };

        $this["User"] = function ($c) {
            $db =  $c->get("Db");
            $task = new \App\Models\User($db->getConnection());
            return $task;
        };

        $this["Machine"] = function ($c) {
            $db =  $c->get("Db");
            $task = new \App\Models\Machine($db->getConnection());
            return $task;
        };
    }
}
