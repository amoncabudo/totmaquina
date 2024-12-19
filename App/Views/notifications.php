<!DOCTYPE html>
<html lang="es">
<link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/main.css">
</head>
<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="container mx-auto px-4 pt-24 pb-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Encabezado -->
            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                <h2 class="text-lg font-semibold text-gray-900">Todas las Notificaciones</h2>
            </div>

            <!-- Lista de Notificaciones -->
            <div class="divide-y divide-gray-200">
                <?php foreach ($notifications as $notification): ?>
                    <div class="flex px-4 py-3 hover:bg-gray-50 transition-colors <?= $notification['status'] === 'read' ? 'opacity-75' : '' ?>">
                        <div class="flex-shrink-0">
                            <div class="p-2 <?= $notification['icon_bg'] ?> rounded-lg">
                                <svg class="w-5 h-5 <?= $notification['icon_color'] ?>" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <?= $controller->getIcon($notification['type']) ?>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full pl-3">
                            <div class="text-gray-500 text-sm mb-1.5">
                                <span class="font-semibold text-gray-900"><?= htmlspecialchars($notification['title']) ?></span>: 
                                <?= htmlspecialchars($notification['message']) ?>
                            </div>
                            <div class="text-xs <?= $notification['time_color'] ?>">Hace <?= htmlspecialchars($notification['time']) ?></div>
                        </div>
                        <button class="ml-auto" onclick="deleteNotification(<?= $notification['id'] ?>)">
                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
                <nav class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Anterior
                            </a>
                        <?php endif; ?>
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Siguiente
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando <span class="font-medium"><?= ($pagination['current_page'] - 1) * $pagination['per_page'] + 1 ?></span> 
                                a <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span> 
                                de <span class="font-medium"><?= $pagination['total'] ?></span> resultados
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="?page=<?= $pagination['current_page'] - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Anterior</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <a href="?page=<?= $i ?>" 
                                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="?page=<?= $pagination['current_page'] + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Siguiente</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    </div>
                </nav>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function deleteNotification(id) {
        if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
            fetch(`/notifications/delete/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la página o eliminar la notificación del DOM
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ha ocurrido un error al eliminar la notificación');
            });
        }
    }
    </script>

    <script src="/js/main.js"></script>
    <script src="/js/flowbite.min.js"></script>
    <script src="/js/bundle.js"></script>
</body>
</html> 