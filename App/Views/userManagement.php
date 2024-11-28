<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</head>

<body class="bg-gray-300">

    <!-- Contenedor principal -->
    <div class="max-w-4xl mx-auto mt-8 relative">
        <!-- Título principal -->
        <h1 class="text-center text-xl font-bold uppercase text-black">Añadir Usuarios</h1>

        <!-- Botón para abrir el modal -->
        <div class="flex justify-end mt-4">
            <button data-modal-target="user-modal" data-modal-toggle="user-modal"
                class="bg-gray-800 text-white hover:bg-gray-700 focus:ring-4 focus:ring-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </button>
        </div>

        <!-- Modal -->
        <div id="user-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Encabezado -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900">Añadir Usuario</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="user-modal">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Cuerpo -->
                    <div class="p-6">
                        <form action="/addUser" method="POST" enctype="multipart/form-data">
                            <div class="grid gap-4">
                                <!-- Nombre -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-900">Nombre</label>
                                    <input type="text" id="name" name="name" placeholder="Escribe el nombre" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Apellido -->
                                <div>
                                    <label for="surname" class="block text-sm font-medium text-gray-900">Apellido</label>
                                    <input type="text" id="surname" name="surname" placeholder="Escribe el apellido"
                                        required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-900">Correo Electrónico</label>
                                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Contraseña -->
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-900">Contraseña</label>
                                    <input type="password" id="password" name="password" placeholder="Escribe la contraseña" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Rol -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-900">Rol</label>
                                    <select id="role" name="role" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="technician">Technician</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="administrator">Administrator</option>
                                    </select>
                                </div>
                                <!-- Avatar -->
                                <div>
                                    <label for="avatar" class="block text-sm font-medium text-gray-900">Avatar</label>
                                    <input type="file" id="avatar" name="avatar"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">Cancelar</button>
                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de usuarios -->
    <div class="mt-6 bg-white rounded-lg shadow-md px-6 py-6 w-3/4 mx-auto">
    <?php foreach ($users as $user) : ?>
        <div class="flex items-center justify-between mb-4">
            <!-- Contenedor de usuario -->
            <div class="flex items-center space-x-4">
                <!-- Imagen de usuario -->
                <img src="<?= '/Images/' . $user["avatar"]; ?>" alt="Usuario" class="h-12 w-12 rounded-full object-cover">
                <!-- Información de usuario -->
                <p class="text-gray-800 font-semibold"><?= htmlspecialchars($user['name'] . ' ' . $user['surname']); ?></p>
            </div>
            <!-- Acciones de edición y eliminación -->
            <div class="flex space-x-4 items-center">
                <!-- Botón editar -->
                <button class="text-gray-500 hover:text-blue-600 transition-colors duration-300" aria-label="Editar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 20h9m-9-4h6M6 12h3m-3 8h18m-18-8h8m-8-4h9m-9-8h18m-6 8h-3" />
                    </svg>
                </button>
                <!-- Botón eliminar -->
                <button class="text-red-500 hover:text-red-700 transition-colors duration-300" aria-label="Eliminar">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

</body>

</html>