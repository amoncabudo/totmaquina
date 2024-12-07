<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/main.css">
    <title>Nueva Contraseña</title>
</head>
<body>
    <div class="container">
        <form class="w-full max-w-md mx-auto mt-14 text-center border rounded-xl shadow bg-webColor p-5" method="POST" action="/update-password">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token ?? '') ?>">
            
            <?php if (isset($error)): ?>
                <div class="text-red-500 mb-4"><?php echo htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <p class="text-buttonColor">Introduce tu nueva contraseña</p>
            <br>
            <div class="mb-6">
                <div class="relative">
                    <input type="password" id="contrasena" name="contrasena" required
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-buttonColor peer"
                        placeholder=" " />
                    <label for="contrasena"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-buttonColor peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Contraseña</label>
                </div>
            </div>
            <div class="mb-6">
                <div class="relative">
                    <input type="password" id="contrasena2" name="contrasena2" required
                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-buttonColor peer"
                        placeholder=" " />
                    <label for="contrasena2"
                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-buttonColor peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Repetir contraseña</label>
                </div>
            </div>
            <div id="mss"></div>
            <button id="btnEnviar" type="submit"
                class="bg-webColor mx-auto text-buttonColor hover:bg-buttonColor hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 p-2.5 mt-5 text-center">Enviar</button>
        </form>
    </div>
    <script src="/js/flowbite.min.js"></script>
    <script src="/js/bundle.js"></script>
</body>
</html> 