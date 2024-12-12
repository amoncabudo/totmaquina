<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@1.4.7/dist/flowbite.min.css" rel="stylesheet">
    <title>Nueva Contraseña</title>
</head>

<body class="bg-gray-100">

    <div class="flex justify-center items-center h-screen">
        <form class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg" method="POST" action="/NuevaPassword">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <!-- Error message -->
            <?php if (isset($error)): ?>
                <div class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Introduce tu nueva contraseña</h2>

            <!-- Contraseña input -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="block w-full p-3 text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Ingresa tu nueva contraseña">
            </div>

            <!-- Repetir contraseña input -->
            <div class="mb-6">
                <label for="password2" class="block text-sm font-medium text-gray-700 mb-2">Repetir contraseña</label>
                <input type="password" id="password2" name="password2" required
                    class="block w-full p-3 text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Repite tu nueva contraseña">
            </div>

            <!-- Botón Enviar -->
            <button type="submit" class="w-full bg-blue-500 text-white font-medium py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Enviar
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.4.7/dist/flowbite.min.js"></script>
</body>

</html>
