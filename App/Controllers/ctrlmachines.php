<?php

namespace App\Controllers;

use \App\Models\MachineView;
use \Emeset\Contracts\Http\Request;
use \Emeset\Contracts\Http\Response;
use \Emeset\Contracts\Container;

class ctrlmachines
{
    // Function to retrieve all machines
    public function getAllMachines() {
        // Define the SQL query to fetch machine details (id, name, and description)
        $query = "SELECT id, name, description FROM machines"; // Adjust the query based on your database structure

        // Prepare the SQL query
        $stmt = $this->db->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch all the results as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
