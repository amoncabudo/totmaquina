<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="css/main.css">
</head>

<body class="dark:bg-gray-200 flex items-center justify-center min-h-screen">
  <div class="flex flex-col items-center space-y-6 px-4 sm:px-6 lg:px-8">
    <!-- Logo -->
    <img src="image/logo.png" alt="Logo" class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-32 lg:h-32">
    
    <!-- Login Container -->
    <div class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg p-6 sm:p-8 space-y-6 bg-black rounded-lg shadow-lg">
      <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-center text-white">Inicia sesión en tu cuenta</h2>
      
      <!-- Formulario -->
      <form class="space-y-4" action="/validar-login" method="post">
        <!-- Campo Correo -->
        <div>
          <label for="email" class="block mb-2 text-sm font-medium text-white">Correo electrónico</label>
          <input type="text" name="email" id="email" 
                 class="w-full p-2.5 bg-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                 placeholder="Correo electrónico de ejemplo" required>
        </div>

        <!-- Campo Contraseña -->
        <div>
          <label for="password" class="block mb-2 text-sm font-medium text-white">Contraseña</label>
          <input type="password" name="password" id="password" 
                 class="w-full p-2.5 bg-gray-700 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                 placeholder="Contraseña de ejemplo" required>
        </div>

        <!-- Opciones Adicionales -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
            <label for="remember" class="ml-2 text-sm text-white">Recuérdame</label>
          </div>
          <a href="#" class="text-sm text-blue-500 hover:underline">¿Olvidaste la contraseña?</a>
        </div>

        <!-- Botón Enviar -->
        <button type="submit" 
                class="w-full py-2.5 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
          Inicia sesión
        </button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="/js/bundle.js"></script>
  <script src="/js/flowbite.min.js"></script>
</body>

</html>
