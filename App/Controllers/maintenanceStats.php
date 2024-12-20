<?php

function maintenanceStats($request, $response, $container) {
    try {
        // Log a message to indicate the start of the maintenance statistics retrieval process
        error_log("=== DEBUG: Retrieving maintenance statistics ===");
        
        // Create a new database connection
        $db = new \App\Models\Db("grup7", "*Grup777*", "totmaquina", "hl1373.dinaserver.com");
        // Instantiate the Maintenance model with the database connection
        $maintenance = new \App\Models\Maintenance($db->getConnection());
        
        // Retrieve the maintenance statistics
        $stats = $maintenance->getMaintenanceStats();
        // Log the retrieved statistics for debugging purposes
        error_log("Statistics retrieved: " . print_r($stats, true));
        
        // Define translations for maintenance types
        $typeTranslations = [
            'preventive' => 'Preventive', // Translate 'preventive' to 'Preventive'
            'corrective' => 'Corrective'  // Translate 'corrective' to 'Corrective'
        ];

        // Prepare data for the charts
        $chartData = [
            'byType' => [
                // Map over the maintenance types and translate them
                'labels' => array_map(function($item) use ($typeTranslations) {
                    return $typeTranslations[$item['type']] ?? $item['type']; // Translate or retain the type if no translation exists
                }, $stats['by_type']),
                // Extract the count of each maintenance type
                'data' => array_column($stats['by_type'], 'count')
            ],
            'monthly' => [
                // Format the months to display as 'M Y' (e.g., Jan 2024)
                'labels' => array_map(function($item) {
                    $date = \DateTime::createFromFormat('Y-m', $item['month']);
                    return $date ? $date->format('M Y') : $item['month']; // Format or retain the month string if formatting fails
                }, $stats['monthly']),
                // Extract the count of maintenance per month
                'data' => array_column($stats['monthly'], 'count')
            ],
            'byMachine' => [
                // Extract machine names for the chart
                'labels' => array_column($stats['by_machine'], 'machine_name'),
                // Extract the total count of maintenance for each machine
                'data' => array_column($stats['by_machine'], 'maintenance_count'),
                // Extract the completed maintenance count for each machine
                'completed' => array_column($stats['by_machine'], 'completed_count')
            ]
        ];
        
        // Set the statistics and chart data to be passed to the view
        $response->set('stats', $stats);
        $response->set('chartData', $chartData);
        
        // Set the template to render the maintenance statistics page
        $response->setTemplate("maintenance_stats.php");
        return $response;
        
    } catch (\Exception $e) {
        // Log any errors that occur during the process
        error_log("Error retrieving statistics: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        
        // Set the error message to be displayed in the view
        $response->set('error', $e->getMessage());
        // Set the template to render the maintenance statistics page with the error message
        $response->setTemplate("maintenance_stats.php");
        return $response;
    }
}
