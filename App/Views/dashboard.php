<?php
// Asegúrate de que la sesión esté iniciada
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit();
}

// Resto del código del dashboard...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <?php include __DIR__ . "/layouts/navbar.php"; ?>

    <main class="bg-gray-100 min-h-screen">
        <!-- Carrusel de imágenes -->
        <div class="w-full bg-gray-700 h-64 mb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center justify-center">
                <div class="w-32 h-32">
                    <img src="/img/placeholder.svg" alt="Placeholder" class="w-full h-full object-cover">
                </div>
                <!-- Puntos de navegación del carrusel -->
                <div class="absolute bottom-4 flex space-x-2">
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full"></span>
                </div>
            </div>
        </div>

        <!-- Sección TOT MÀQUINA -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-gray-700 rounded-lg p-8 flex items-center justify-between">
                <div class="max-w-2xl">
                    <h2 class="text-2xl font-bold text-white mb-4">TOT MÀQUINA</h2>
                    <p class="text-gray-300">
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Veniam, maiores 
                        officia. Assumenda quisquam, recusandae facere aspernatur laboriosam eaque 
                        rem expedita dolorem nam nesciunt quas minus eligendi voluptatum cum. Accusantium.
                    </p>
                </div>
                <div class="w-32 h-32 flex-shrink-0">
                    <img src="/img/placeholder.svg" alt="Máquina" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . "/layouts/footer.php"; ?>
</body>
</html> 