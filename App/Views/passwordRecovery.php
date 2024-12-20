<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/css/main.css">
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
</head>

<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto p-6">
        <div class="bg-gray-800 rounded-xl shadow-lg p-8">
            <div class="text-center mb-8">
                <img src="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png" alt="Logo" class="w-20 mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">Recuperar Contraseña</h1>
                <p class="text-gray-400 mt-2">Ingresa tu correo electrónico para recuperar tu contraseña</p>
            </div>

            <form action="/reset" method="POST" class="space-y-6">
                <!-- Campo de Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">Correo Electrónico</label>
                    <input type="email"
                        id="email"
                        name="email"
                        required
                        class="mt-1 block w-full px-4 py-2 bg-gray-700 text-gray-200 border border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="ejemplo@correo.com">
                </div>

                <!-- Botón de Enviar -->
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Enviar Correo
                </button>
            </form>

            <!-- Enlace para volver -->
            <div class="text-center mt-6">
                <a href="/login" class="text-sm text-blue-500 hover:text-blue-400">
                    Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</body>

</html>
