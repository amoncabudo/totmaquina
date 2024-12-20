<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
    <script src="/js/bundle.js"></script>
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-6">
        <form class="bg-gray-800 rounded-xl shadow-lg p-8 w-full max-w-md" method="POST" action="/NuevaPassword">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <!-- Error message -->
            <?php if (isset($error)): ?>
                <div class="text-red-500 mb-4 text-center"><?php echo htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <img src="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png" alt="Logo" class="w-20 mx-auto mb-4">

            <h2 class="text-3xl font-bold text-center text-white mb-6">Introduce tu nueva contraseña</h2>

            <!-- Contraseña input -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                <input type="password" id="password" name="password" required
                    class="block w-full p-3 bg-gray-700 text-gray-200 rounded-md border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ingresa tu nueva contraseña">
                <span id="mss" class="text-sm"></span>
            </div>

            <div class="mb-6">
                <label for="password2" class="block text-sm font-medium text-gray-300 mb-2">Repetir contraseña</label>
                <input type="password" id="password2" name="password2" required
                    class="block w-full p-3 bg-gray-700 text-gray-200 rounded-md border border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Repite tu nueva contraseña">
                <span id="mss2" class="text-sm"></span>
            </div>
            <!-- Botón Enviar -->
            <button id="btnEnviar" type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Enviar
            </button>
        </form>
    </div>
</body>

</html>