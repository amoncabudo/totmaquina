<?php
function maintenanceStats($request, $response, $container) {
    $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
    $maintenance = new \App\Models\Maintenance($db->getConnection());
    
    // Obtener estadísticas
    $stats = $maintenance->getMaintenanceStats();
    $response->set("stats", $stats);
    
    $response->setTemplate('maintenance_stats.php');
    return $response;
} 