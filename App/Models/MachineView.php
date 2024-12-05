<?php
namespace App\Models;

class MachineView
{
    /**
     * @var \PDO
     */
    private $sql;

    /**
     * __construct: Crear el modelo MachineView
     * 
     * @param \PDO $conn Conexion a la base de datos
     */
    public function __construct(\PDO $conn)
    {
        $this->sql = $conn;
    }

    /**
     * Obtener todas las máquinas
     * 
     * @return array
     */
    public function getAllMachines()
    {
        $stmt = $this->sql->prepare("SELECT id, name, model, manufacturer, location, installation_date, coordinates FROM Machine ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Si necesitas obtener más información sobre una máquina, puedes crear más métodos.
}
