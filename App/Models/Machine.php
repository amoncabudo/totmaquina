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
        $stmt = $this->sql->prepare("SELECT id, name, model, manufacturer, location, installation_date, coordinates FROM Machine ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMachineById($id) {
        $stmt = $this->sql->prepare("SELECT * FROM Machine WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

     
    public function updateMachine($id, $name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates)
    {

        $query = "UPDATE Machine SET name = :name, model = :model, manufacturer = :manufacturer, location = :location, installation_date = :installation_date, serial_number = :serial_number, photo = :photo, coordinates = :coordinates WHERE id = :id";
        $stmt = $this->sql->prepare($query);
        
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'model' => $model,
            'manufacturer' => $manufacturer,
            'location' => $location,
            'installation_date' => $installation_date,
            'serial_number' => $serial_number,
            'photo' => $photo,
            'coordinates' => $coordinates
        ]);
    }

    public function insertMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates){
        try {
            $query = "INSERT INTO Machine (name, model, manufacturer, location, installation_date, serial_number, photo, coordinates) VALUES (:name, :model, :manufacturer, :location, :installation_date, :serial_number, :photo, :coordinates)";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'name' => $name,
                'model' => $model,
                'manufacturer' => $manufacturer,
                'location' => $location,
                'installation_date' => $installation_date,
                'serial_number' => $serial_number,
                'photo' => $photo,
                'coordinates' => $coordinates
            ]);
        } catch (\PDOException $e) {
            echo "Error en la inserción: " . $e->getMessage();
            exit;
        }
    }
    
    public function deleteMachine($id){
        $query = "DELETE FROM Machine WHERE id = :id";
        $stm = $this->sql->prepare($query);
        $stm->execute([":id" => $id]);
    
        if ($stm->errorCode() !== '00000') {
            $err = $stm->errorInfo();
            die("Error al eliminar: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }
    
    public function assignUserToMachine($machineId, $userId) {
        $query = "UPDATE Machine SET assigned_technician_id = :userId WHERE id = :machineId";
        $stmt = $this->sql->prepare($query);
        $stmt->execute([
            ':machineId' => $machineId,
            ':userId' => $userId
        ]);

        if ($stmt->errorCode() !== '00000') {
            $err = $stmt->errorInfo();
            die("Error al asignar usuario: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }

    public function getMachineBySerialNumber($serial_number) {
        $query = "SELECT * FROM Machine WHERE serial_number = :serial_number";
        $stmt = $this->sql->prepare($query);
        $stmt->execute(['serial_number' => $serial_number]);
        return $stmt->fetch();
    }

    public function createMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates) {
        try {
            $query = "INSERT INTO Machine (name, model, manufacturer, location, installation_date, serial_number, photo, coordinates) VALUES (:name, :model, :manufacturer, :location, :installation_date, :serial_number, :photo, :coordinates)";
            $stmt = $this->sql->prepare($query);
            $stmt->execute([
                'name' => $name,
                'model' => $model,
                'manufacturer' => $manufacturer,
                'location' => $location,
                'installation_date' => $installation_date,
                'serial_number' => $serial_number,
                'photo' => $photo,
                'coordinates' => $coordinates
            ]);
        } catch (\PDOException $e) {
            echo "Error en la inserción: " . $e->getMessage();
            exit;
        }
    }
}
