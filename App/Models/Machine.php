<?php

/**
 * Exemple per a M07 i M08.
 * Model que gestiona les tasques amb PDO.
 * 
 * @autor: Dani Prados dprados@cendrassos.net
 */

namespace App\Models;

/**
 * Tasques: model que gestiona les tasques.
 * Per guardar, recuperar i gestionar les tasques.
 * 
 * @autor: Dani Prados dprados@cendrassos.net
 */
class Machine
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el model tasques
     * Model adaptat per PDO
     * 
     * @param \App\Models\Db $conn connexió a la base de dades
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * getAllUser: obtenir tots els usuaris
     * 
     * @return array
     */
    public function getAllMachine(){
        $stmt = $this->sql->prepare("SELECT id, name, model, manufacturer, location, installation_date FROM Machine ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMachineById($id) {
        $stmt = $this->sql->prepare("SELECT * FROM Machine WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    
    public function addMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number){

        $query = "INSERT INTO Machine (name, model, manufacturer, location, installation_date, serial_number) VALUES (:name, :model, :manufacturer, :location, :installation_date, :serial_number);";
        $stm = $this->sql->prepare($query);
        //print_r([":event_title" => $event_title, ":event_description" => $event_description, ":event_location" => $event_location, ":event_type" => $event_type, ":event_comment" => $event_comment_value, ":date_start" => $date_start, ":date_end" => $date_end]);
        $stm->execute([":name" => $name, ":model" => $model, ":manufacturer" => $manufacturer, ":location" => $location, ":installation_date" => $installation_date, ":serial_number" => $serial_number]);

        $id = $this->sql->lastInsertId();
        return $id;
    }

     
    public function updateMachine($id, $name, $model, $manufacturer, $location, $installation_date, $serial_number) {
    
        $query = "UPDATE Machine SET name = :name, model = :model, manufacturer = :manufacturer, location = :location, installation_date = :installation_date, serial_number = :serial_number WHERE id = :id";
        $stm = $this->sql->prepare($query);
        $stm->execute([
            ":id" => $id,
            ":name" => $name,
            ":model" => $model,
            ":manufacturer" => $manufacturer,
            ":location" => $location,
            ":installation_date" => $installation_date,
            ":serial_number" => $serial_number
        ]);
    
        if ($stm->errorCode() !== '00000') {
            $err = $stm->errorInfo();
            die("Error al actualizar: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }
}
