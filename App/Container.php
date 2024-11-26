<?php


<<<<<<< HEAD
=======

>>>>>>> develop
namespace App;

use Emeset\Container as EmesetContainer;

<<<<<<< HEAD
=======

>>>>>>> develop
class Container extends EmesetContainer {

    public function __construct($config){
        parent::__construct($config);
        
        $this["\App\Controllers\Privat"] = function ($c) {
            // Aqui podem inicialitzar totes les dependències del controlador i passar-les com a paràmetre.
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
    }

}
