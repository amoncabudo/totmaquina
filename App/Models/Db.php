<?php

namespace App\Models;

class Db {

    public $sql;

    // Constructor to initialize the database connection
    public function __construct($user, $pass, $db, $host){
        $dsn = "mysql:dbname={$db};host={$host}";
        try {
            $this->sql = new \PDO($dsn, $user, $pass);
        } catch (\PDOException $e) {
            die('Ha fallat la conexio ' . $e->getMessage());
        }
    }

    // Function to get the database connection
    public function getConnection(){
        return $this->sql;
    }
}
