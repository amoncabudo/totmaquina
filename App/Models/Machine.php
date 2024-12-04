<?php

namespace App\Models;

class Machine
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el modelo de máquinas
     * 
     * @param \PDO $conn conexión a la base de datos
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * getAllMachine: Obtener todas las máquinas
     * 
     * @return array
     */
    public function getAllMachine()
    {
        $stmt = $this->sql->prepare("SELECT id, name, model, manufacturer, location, installation_date, coordinates FROM Machine ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * getMachineById: Obtener una máquina por su ID
     * 
     * @param int $id
     * @return array
     */
    public function getMachineById($id)
    {
        $stmt = $this->sql->prepare("SELECT * FROM Machine WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * updateMachine: Actualizar una máquina
     * 
     * @param int $id
     * @param string $name
     * @param string $model
     * @param string $manufacturer
     * @param string $location
     * @param string $installation_date
     * @param string $serial_number
     * @param string $photo
     * @param string $coordinates
     */
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

    /**
     * insertMachine: Insertar una nueva máquina
     * 
     * @param string $name
     * @param string $model
     * @param string $manufacturer
     * @param string $location
     * @param string $installation_date
     * @param string $serial_number
     * @param string $photo
     * @param string $coordinates
     */
    public function insertMachine($name, $model, $manufacturer, $location, $installation_date, $serial_number, $photo, $coordinates)
    {
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

    /**
     * deleteMachine: Eliminar una máquina por su ID
     * 
     * @param int $id
     */
    public function deleteMachine($id)
    {
        $query = "DELETE FROM Machine WHERE id = :id";
        $stmt = $this->sql->prepare($query);
        $stmt->execute([":id" => $id]);
    
        if ($stmt->errorCode() !== '00000') {
            $err = $stmt->errorInfo();
            die("Error al eliminar: {$err[0]} - {$err[1]}\n{$err[2]}");
        }
    }

    /**
     * assignUserToMachine: Asignar un usuario a una máquina
     * 
     * @param int $machineId
     * @param int $userId
     */
    public function assignUserToMachine($machineId, $userId)
    {
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

    /**
     * getMachineBySerialNumber: Obtener una máquina por su número de serie
     * 
     * @param string $serial_number
     * @return array
     */
    public function getMachineBySerialNumber($serial_number)
    {
        $query = "SELECT * FROM Machine WHERE serial_number = :serial_number";
        $stmt = $this->sql->prepare($query);
        $stmt->execute(['serial_number' => $serial_number]);
        return $stmt->fetch();
    }
}

