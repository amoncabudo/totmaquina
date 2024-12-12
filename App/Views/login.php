<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TOT Màquina</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png">
    <script src="/js/bundle.js"></script>
</head>

<body class="bg-gray-900 min-h-screen">
    <!-- Círculos decorativos animados -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gray-950 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gray-950 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-gray-950 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-float" style="animation-delay: 4s;"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl p-8 transform transition-all duration-500 hover:scale-[1.02]">
            <!-- Logo con animación -->
            <div class="flex justify-center mb-8">
                <img class="h-32 w-auto animate-float" src="/Images/6748b003c2a02_imagen_2024-11-28_150432915-removebg-preview.png" alt="TOT Màquina Logo">
            </div>
            
            <!-- Título con efecto de aparición -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">
                    Iniciar Sesión
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Accede a tu cuenta para continuar
                </p>
            </div>

            <!-- Mensaje de error -->
            <?php if (isset($error) && $error !== ""): ?>
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 transform transition-all duration-500 hover:scale-[1.02]" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form class="space-y-6" action="/validar-login" method="post">
                <!-- Email -->
                <div class="transform transition-all duration-300 hover:translate-x-1">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Correo Electrónico
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-900 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="appearance-none pl-10 block w-full px-3 py-3 bg-white border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-300"
                            placeholder="ejemplo@correo.com">
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="transform transition-all duration-300 hover:translate-x-1">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-900 transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="appearance-none pl-10 block w-full px-3 py-3 bg-white border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-transparent transition-all duration-300"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember me y Forgot password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-300 rounded transition-colors duration-200">
                        <label for="remember" class="ml-2 block text-sm text-gray-600">
                            Recordarme
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="/passwordRecovery" class="font-medium text-gray-600 hover:text-gray-900 transition-colors duration-200">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>

                <!-- Botón de login -->
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-medium text-white bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>