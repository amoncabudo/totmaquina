<?php
namespace App\Controllers;

use App\Models\Notification;

class NotificationsController {
    public function __construct() {
        // Constructor (currently empty, can be used for initializing any required properties or dependencies)
    }

    public function index($request, $response, $container) {
        // Here we will fetch notifications from the database
        // For now, we are using sample data

        // Sample notifications data (this would typically come from a database)
        $notifications = [
            [
                'id' => 1,
                'type' => 'new_incident',
                'title' => 'New incident registered',
                'message' => 'Machine CNC-001 requires urgent maintenance',
                'time' => '10 minutes',
                'status' => 'unread',
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600',
                'time_color' => 'text-blue-600'
            ],
            [
                'id' => 2,
                'type' => 'maintenance_complete',
                'title' => 'Maintenance completed',
                'message' => 'Machine PRESS-002 is ready to operate',
                'time' => '2 hours',
                'status' => 'unread',
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-600',
                'time_color' => 'text-green-600'
            ],
            [
                'id' => 3,
                'type' => 'warning',
                'title' => 'Maintenance warning',
                'message' => 'Machine MILL-003 requires scheduled inspection',
                'time' => '1 day',
                'status' => 'read',
                'icon_bg' => 'bg-yellow-100',
                'icon_color' => 'text-yellow-600',
                'time_color' => 'text-yellow-600'
            ],
            [
                'id' => 4,
                'type' => 'error',
                'title' => 'Error detected',
                'message' => 'Critical failure in machine ROBOT-004',
                'time' => '3 days',
                'status' => 'read',
                'icon_bg' => 'bg-red-100',
                'icon_color' => 'text-red-600',
                'time_color' => 'text-red-600'
            ]
        ];

        // Pagination logic
        $total = count($notifications);  // Total number of notifications
        $per_page = 4;  // Number of notifications to display per page
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Get the current page from the query string, default to 1
        $total_pages = ceil($total / $per_page);  // Calculate the total number of pages

        // Set data for the view
        $response->set("notifications", $notifications);
        $response->set("pagination", [
            'total' => $total,
            'per_page' => $per_page,
            'current_page' => $current_page,
            'total_pages' => $total_pages
        ]);
        $response->set("controller", $this);  // Pass the controller itself to the view (if needed)

        // Set the template to be rendered
        $response->setTemplate("notifications.php");

        return $response;  // Return the response with the notifications data and template
    }

    public function markAsRead($request, $response, $container) {
        $id = $request->getParam("id");  // Get the notification ID from the request parameters
        // Here, we would mark the notification as read in the database (this functionality is not implemented yet)
        
        // Return a JSON response indicating success
        $response->setJson([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
        return $response;
    }

    public function delete($request, $response, $container) {
        $id = $request->getParam("id");  // Get the notification ID from the request parameters
        // Here, we would delete the notification from the database (this functionality is not implemented yet)
        
        // Return a JSON response indicating success
        $response->setJson([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
        return $response;
    }

    public function getIcon($type) {
        // Define the SVG icon paths for different notification types
        $icons = [
            'new_incident' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>',
            'maintenance_complete' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
            'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
            'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        ];

        // Return the icon for the given notification type, or a default icon if the type is not found
        return $icons[$type] ?? $icons['warning'];
    }
}
