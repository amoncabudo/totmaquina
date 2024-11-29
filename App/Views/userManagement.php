<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body class="bg-gray-100">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <!-- Contenedor principal -->
    <div class="container mx-auto px-4 pt-24 pb-8">
        <!-- Título y botón -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h1>
            <button data-modal-target="user-modal" data-modal-toggle="user-modal"
                class="bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Añadir Usuario
            </button>
        </div>

        <!-- Modal -->
        <div id="user-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow-xl">
                    <!-- Encabezado -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">Añadir Usuario</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900 transition-colors" data-modal-hide="user-modal">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="p-6">
                        <form action="/addUser" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                    <input type="text" id="name" name="name" placeholder="Escribe el nombre" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Apellido -->
                                <div>
                                    <label for="surname" class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
                                    <input type="text" id="surname" name="surname" placeholder="Escribe el apellido" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Contraseña -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                                    <input type="password" id="password" name="password" placeholder="••••••••" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Rol -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                                    <select id="role" name="role" required
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="technician">Técnico</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="administrator">Administrador</option>
                                    </select>
                                </div>
                                <!-- Avatar -->
                                <div>
                                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Avatar</label>
                                    <input type="file" id="avatar" name="avatar" accept="image/*"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" data-modal-hide="user-modal"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
                                    Cancelar
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de usuarios -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <?php foreach ($users as $user) : ?>
                <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors border-b border-gray-200 last:border-b-0">
                    <div class="flex items-center space-x-4">
                        <img src="<?= '/Images/' . $user["avatar"]; ?>" alt="Avatar de <?= htmlspecialchars($user['name']); ?>" 
                             class="h-12 w-12 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <p class="text-gray-800 font-semibold"><?= htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
                            <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['email']); ?></p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                title="Editar usuario">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                title="Eliminar usuario">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="/js/main.js"></script>
</body>
</html>