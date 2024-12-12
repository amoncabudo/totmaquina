<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Recuperación de Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-800 to-gray-900 min-h-screen">
    <!-- Círculos decorativos -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-red-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-red-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 transform transition-all duration-500 hover:scale-[1.02]">
            <!-- Icono de Error -->
            <div class="flex justify-center mb-8">
                <div class="rounded-full bg-red-500/20 p-4">
                    <svg class="h-16 w-16 text-red-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    ¡Ups! Algo salió mal
                </h2>
                <p class="text-gray-300 mb-8">
                    Ha ocurrido un error al procesar tu solicitud de recuperación de contraseña. Por favor, inténtalo de nuevo más tarde.
                </p>

                <!-- Botones de acción -->
                <div class="space-y-4">
                    <a href="/passwordRecovery" 
                       class="w-full inline-flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg">
                        Intentar de nuevo
                    </a>
                    
                    <a href="/login" 
                       class="w-full inline-flex justify-center py-3 px-4 border border-gray-500/30 rounded-xl shadow-md text-sm font-medium text-gray-300 hover:text-white hover:border-gray-400/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300">
                        Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 