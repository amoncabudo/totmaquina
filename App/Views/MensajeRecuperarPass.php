<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correo Enviado - Recuperación de Contraseña</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-800 to-gray-900 min-h-screen">
    <!-- Círculos decorativos -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500/30 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 transform transition-all duration-500 hover:scale-[1.02]">
            <!-- Icono de Éxito -->
            <div class="flex justify-center mb-8">
                <div class="rounded-full bg-green-500/20 p-4">
                    <svg class="h-16 w-16 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                    </svg>
                </div>
            </div>

            <div class="text-center">
                <h2 class="text-3xl font-bold text-white mb-4">
                    ¡Correo Enviado!
                </h2>
                <p class="text-gray-300 mb-4">
                    Hemos enviado un correo electrónico con las instrucciones para restablecer tu contraseña.
                </p>
                <p class="text-gray-400 mb-8 text-sm">
                    Si no recibes el correo en los próximos minutos, revisa tu carpeta de spam.
                </p>

                <!-- Información adicional -->
                <div class="bg-white/5 rounded-xl p-4 mb-8">
                    <p class="text-gray-300 text-sm">
                        El enlace de recuperación expirará en 30 minutos por razones de seguridad.
                    </p>
                </div>

                <!-- Botones de acción -->
                <div class="space-y-4">
                    <a href="/login" 
                       class="w-full inline-flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg">
                        Volver al inicio de sesión
                    </a>
                    
                    <a href="/passwordRecovery" 
                       class="w-full inline-flex justify-center py-3 px-4 border border-gray-500/30 rounded-xl shadow-md text-sm font-medium text-gray-300 hover:text-white hover:border-gray-400/40 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300">
                        Solicitar nuevo correo
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 