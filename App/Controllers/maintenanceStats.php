<?php

function maintenanceStats($request, $response, $container) {
    try {
        error_log("=== DEBUG: Obteniendo estadísticas de mantenimiento ===");
        
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        $maintenance = new \App\Models\Maintenance($db->getConnection());
        
        // Obtener las estadísticas
        $stats = $maintenance->getMaintenanceStats();
        error_log("Estadísticas obtenidas: " . print_r($stats, true));
        
        // Traducir tipos de mantenimiento
        $typeTranslations = [
            'preventive' => 'Preventivo',
            'corrective' => 'Correctivo'
        ];

        // Preparar los datos para los gráficos
        $chartData = [
            'byType' => [
                'labels' => array_map(function($item) use ($typeTranslations) {
                    return $typeTranslations[$item['type']] ?? $item['type'];
                }, $stats['by_type']),
                'data' => array_column($stats['by_type'], 'count')
            ],
            'monthly' => [
                'labels' => array_map(function($item) {
                    $date = \DateTime::createFromFormat('Y-m', $item['month']);
                    return $date ? $date->format('M Y') : $item['month'];
                }, $stats['monthly']),
                'data' => array_column($stats['monthly'], 'count')
            ],
            'byMachine' => [
                'labels' => array_column($stats['by_machine'], 'machine_name'),
                'data' => array_column($stats['by_machine'], 'maintenance_count'),
                'completed' => array_column($stats['by_machine'], 'completed_count')
            ]
        ];
        
        // Pasar los datos a la vista
        $response->set('stats', $stats);
        $response->set('chartData', $chartData);
        
        // Establecer la plantilla
        $response->setTemplate("maintenance_stats.php");
        return $response;
        
    } catch (\Exception $e) {
        error_log("Error al obtener estadísticas: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        // Establecer la plantilla y mostrar el error en la misma página
        $response->set('error', $e->getMessage());
        $response->setTemplate("maintenance_stats.php");
        return $response;
    }
} 