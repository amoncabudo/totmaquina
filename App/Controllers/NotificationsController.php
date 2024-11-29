<?php
namespace App\Controllers;

use App\Models\Notification;

class NotificationsController {
    public function __construct() {
        // Constructor
    }

    public function index($request, $response, $container) {
        // Aquí obtendremos las notificaciones de la base de datos
        // Por ahora usaremos datos de ejemplo
        $notifications = [
            [
                'id' => 1,
                'type' => 'new_incident',
                'title' => 'Nueva incidencia registrada',
                'message' => 'Máquina CNC-001 requiere mantenimiento urgente',
                'time' => '10 minutos',
                'status' => 'unread',
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600',
                'time_color' => 'text-blue-600'
            ],
            [
                'id' => 2,
                'type' => 'maintenance_complete',
                'title' => 'Mantenimiento completado',
                'message' => 'Máquina PRESS-002 lista para operar',
                'time' => '2 horas',
                'status' => 'unread',
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-600',
                'time_color' => 'text-green-600'
            ],
            [
                'id' => 3,
                'type' => 'warning',
                'title' => 'Advertencia de mantenimiento',
                'message' => 'Máquina MILL-003 requiere revisión programada',
                'time' => '1 día',
                'status' => 'read',
                'icon_bg' => 'bg-yellow-100',
                'icon_color' => 'text-yellow-600',
                'time_color' => 'text-yellow-600'
            ],
            [
                'id' => 4,
                'type' => 'error',
                'title' => 'Error detectado',
                'message' => 'Fallo crítico en máquina ROBOT-004',
                'time' => '3 días',
                'status' => 'read',
                'icon_bg' => 'bg-red-100',
                'icon_color' => 'text-red-600',
                'time_color' => 'text-red-600'
            ]
        ];

        // Paginación
        $total = count($notifications);
        $per_page = 4;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total_pages = ceil($total / $per_page);

        $response->set("notifications", $notifications);
        $response->set("pagination", [
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $current_page,
            'total_pages' => $total_pages
        ]);
        $response->set("controller", $this);

        $response->setTemplate("notifications.php");

        return $response;
    }

    public function markAsRead($request, $response, $container) {
        $id = $request->getParam("id");
        // Aquí marcaríamos la notificación como leída en la base de datos
        $response->setJson([
            'success' => true,
            'message' => 'Notificación marcada como leída'
        ]);
        return $response;
    }

    public function delete($request, $response, $container) {
        $id = $request->getParam("id");
        // Aquí eliminaríamos la notificación de la base de datos
        $response->setJson([
            'success' => true,
            'message' => 'Notificación eliminada'
        ]);
        return $response;
    }

    public function getIcon($type) {
        $icons = [
            'new_incident' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
            'maintenance_complete' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
            'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        ];

        return $icons[$type] ?? $icons['warning'];
    }
} 