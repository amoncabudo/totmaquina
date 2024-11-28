<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.tailwindcss.com">
  <link rel="stylesheet" href="css/main.css">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="w-full max-w-sm p-6 bg-dark rounded-lg shadow-md">
    <h2 class="mb-6 text-2xl font-bold text-center text-gray-800">Inicia sesión</h2>
    <form class="space-y-4" action="/validar-login" method="post">
      <div>
        <label for="email" class="block mb-1 text-sm text-gray-600">Correo electrónico</label>
        <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ejemplo@correo.com" required>
      </div>
      <div>
        <label for="password" class="block mb-1 text-sm text-gray-600">Contraseña</label>
        <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="********" required>
      </div>
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <input id="remember" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
          <label for="remember" class="ml-2 text-sm text-gray-600">Recuérdame</label>
        </div>
        <a href="#" class="text-sm text-blue-500 hover:underline">¿Olvidaste la contraseña?</a>
      </div>
      <button type="submit" class="w-full py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Inicia sesión</button>
    </form>
  </div>
</body>

</html>
