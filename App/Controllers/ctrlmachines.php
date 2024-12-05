<?php

namespace App\Controllers;

use \App\Models\MachineView;
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachines
{
    public function getAllMachines() {
        $query = "SELECT id, name, description FROM machines"; // Ajusta tu consulta según la estructura de tu base de datos
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}    
