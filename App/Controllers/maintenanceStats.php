<?php
function maintenanceStats($request, $response, $container) {
    $db = new \App\Models\Db("root", "12345", "totmaquina", "mariadb");
    $maintenance = new \App\Models\Maintenance($db->getConnection());
    
    // Obtener estadísticas
    $stats = $maintenance->getMaintenanceStats();
    $response->set("stats", $stats);
    
    $response->setTemplate('maintenance_stats.php');
    return $response;
} 