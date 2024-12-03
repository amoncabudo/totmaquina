<?php include "layouts/navbar.php"; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white shadow-md rounded-lg">
            <div class="border-b border-gray-200 px-6 py-4">
                <h4 class="text-xl font-semibold text-gray-800">Configuración del Perfil</h4>
            </div>
            <div class="p-6">
                <?php if (isset($error)): ?>
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                        <?= $success ?>
                    </div>
                <?php endif; ?>

                <form action="/update-profile" method="POST">
                    <div class="mb-6">
                        <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Nombre de Usuario</label>
                        <input type="text" id="username" name="username" value="<?= $user['name'] ?>" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" id="email" value="<?= $user['email'] ?>" disabled
                               class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed">
                        <p class="mt-2 text-sm text-gray-500">El email no se puede modificar</p>
                    </div>
                    <div class="mb-6">
                        <label for="currentPassword" class="block mb-2 text-sm font-medium text-gray-900">Contraseña Actual</label>
                        <input type="password" id="currentPassword" name="currentPassword" required
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                    <div class="mb-6">
                        <label for="newPassword" class="block mb-2 text-sm font-medium text-gray-900">Nueva Contraseña</label>
                        <input type="password" id="newPassword" name="newPassword"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <p class="mt-2 text-sm text-gray-500">Dejar en blanco si no desea cambiar la contraseña</p>
                    </div>
                    <button type="submit" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 