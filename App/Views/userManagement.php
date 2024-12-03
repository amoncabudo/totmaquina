<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body class="bg-gray-100 min-h-screen">
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <div class="max-w-7xl mx-auto p-8 rounded-lg">
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Gestión de Usuarios</h1>

        <!-- Botón Añadir Usuario -->
        <div class="flex justify-end mb-4">
            <button data-modal-target="user-modal" data-modal-toggle="user-modal"
                class="bg-gray-800 text-white hover:bg-gray-700 focus:ring-4 focus:ring-gray-600 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Añadir Usuario
            </button>

            <button id="createTestTechnician"
                class="bg-blue-600 text-white hover:bg-blue-700 focus:ring-4 focus:ring-blue-600 font-medium rounded-lg text-sm px-5 py-2.5 mr-2">
                Crear Técnico de Prueba
            </button>
            <button id="createTestSupervisor"
                class="bg-green-600 text-white hover:bg-green-700 focus:ring-4 focus:ring-green-600 font-medium rounded-lg text-sm px-5 py-2.5">
                Crear Supervisor de Prueba
            </button>
        </div>

        <!-- Modal Añadir Usuario -->
        <div id="user-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <div class="bg-white rounded-lg shadow">
                    <!-- Encabezado -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900">Añadir Usuario</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="user-modal">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Cuerpo del Modal -->
                    <div class="p-6">
                        <form action="/addUser" method="POST" enctype="multipart/form-data">
                            <div class="grid gap-4">
                                <!-- Campos del formulario -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-900">Nombre</label>
                                    <input type="text" id="name" name="name" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="surname" class="block text-sm font-medium text-gray-900">Apellido</label>
                                    <input type="text" id="surname" name="surname" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                                    <input type="email" id="email" name="email" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <label for="password" class="block text-sm font-medium text-gray-900">Contraseña</label>
                                        <button data-tooltip-target="password-tooltip" type="button" class="ml-2">
                                            <svg class="w-4 h-4 text-gray-400 hover:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                        <div id="password-tooltip" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                                            La contraseña debe contener:
                                            <ul class="list-disc pl-4">
                                                <li>Al menos una minúscula</li>
                                                <li>Al menos una mayúscula</li>
                                                <li>Al menos un número</li>
                                                <li>Al menos un carácter especial ($@$!%*?&-.,)</li>
                                                <li>Entre 6 y 13 caracteres</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input type="password" id="password" name="password"
                                        placeholder="Escribe la contraseña" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <div id="mss"></div>
                                </div>
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-900">Rol</label>
                                    <select id="role" name="role" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="technician">Technician</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="administrator">Administrator</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="avatar" class="block text-sm font-medium text-gray-900">Avatar</label>
                                    <input type="file" id="avatar" name="avatar"
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700" data-modal-hide="user-modal">Cancelar</button>
                                    <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-700">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <?php foreach ($users as $user): ?>
            <div class="bg-gray-200 p-4 rounded-lg shadow-md mb-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img src="<?= '/Images/' . $user["avatar"]; ?>" alt="Avatar" class="h-12 w-12 rounded-full object-cover">
                        <div>
                            <h2 class="text-lg font-bold"><?= htmlspecialchars($user['name'] . ' ' . $user['surname']) ?></h2>
                            <p class="text-sm text-gray-600"><?= htmlspecialchars($user['role']) ?></p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Botón Editar -->
                        <button type="button"
                            data-modal-target="edit-user-modal-<?= $user['id'] ?>"
                            data-modal-toggle="edit-user-modal-<?= $user['id'] ?>"
                            class="p-2 text-blue-800 hover:bg-blue-50 rounded-lg transition-colors duration-300">
                            <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                            </svg>
                        </button>

                        <!-- Botón Eliminar -->
                        <form action="/deleteUser" method="POST" class="inline">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit"
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')"
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Modales de Edición -->
        <?php foreach ($users as $user) : ?>
            <div id="edit-user-modal-<?= $user['id'] ?>" tabindex="-1" aria-hidden="true"
                class="hidden fixed top-0 left-0 right-0 z-50 flex items-center justify-center w-full p-4 overflow-x-hidden overflow-y-auto h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <div class="bg-white rounded-lg shadow">
                        <!-- Encabezado -->
                        <div class="flex items-center justify-between p-4 border-b border-gray-300">
                            <h3 class="text-lg font-semibold text-gray-900">Editar Usuario</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-900" data-modal-hide="edit-user-modal-<?= $user['id'] ?>">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Cuerpo -->
                        <div class="p-6">
                            <form action="/editUser" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <div class="grid gap-4">
                                    <!-- Campos del formulario de edición -->
                                    <div>
                                        <label for="edit-name-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Nombre</label>
                                        <input type="text" id="edit-name-<?= $user['id'] ?>" name="name"
                                            value="<?= htmlspecialchars($user['name']) ?>" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="edit-surname-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Apellido</label>
                                        <input type="text" id="edit-surname-<?= $user['id'] ?>" name="surname"
                                            value="<?= htmlspecialchars($user['surname']) ?>" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="edit-email-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Email</label>
                                        <input type="email" id="edit-email-<?= $user['id'] ?>" name="email"
                                            value="<?= htmlspecialchars($user['email']) ?>" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="edit-password-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Contraseña</label>
                                        <input type="password" id="edit-password-<?= $user['id'] ?>" name="password"
                                            placeholder="Dejar en blanco para mantener la actual"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="edit-role-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Rol</label>
                                        <select id="edit-role-<?= $user['id'] ?>" name="role" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="technician" <?= $user['role'] === 'technician' ? 'selected' : '' ?>>Technician</option>
                                            <option value="supervisor" <?= $user['role'] === 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                            <option value="administrator" <?= $user['role'] === 'administrator' ? 'selected' : '' ?>>Administrator</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="edit-avatar-<?= $user['id'] ?>" class="block text-sm font-medium text-gray-900">Avatar</label>
                                        <input type="file" id="edit-avatar-<?= $user['id'] ?>" name="avatar"
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <input type="hidden" name="current_avatar" value="<?= htmlspecialchars($user['avatar']) ?>">
                                        <?php if (!empty($user['avatar'])) : ?>
                                            <p class="mt-2 text-sm text-gray-500">Avatar actual: <?= htmlspecialchars($user['avatar']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-700"
                                            data-modal-hide="edit-user-modal-<?= $user['id'] ?>">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="bg-blue-800 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script src="/js/bundle.js"></script>
    <script src="/js/password.js"></script>
    <script src="/js/testUser.js"></script>
</body>

</html>