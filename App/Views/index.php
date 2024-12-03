<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tot Màquina - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
    <link rel="stylesheet" href="/main.css">
</head>
<body class="bg-gray-100">

<?php include __DIR__ . "/layouts/navbar.php"; ?>

<main class="bg-gray-100 min-h-screen">
    <!-- Carrusel de imágenes -->
    <div class="carousel-container">
        <div class="carousel-slide active">
            <img src="/Images/Slider1_Ordenador.webp" alt="Ordenador" data-fancybox="gallery">
        </div>
        <div class="carousel-slide">
            <img src="/Images/Slider2_Pasta.webp" alt="Pasta" data-fancybox="gallery">
        </div>
        <div class="carousel-slide">
            <img src="/Images/Slide3_Empresa.webp" alt="Empresa" data-fancybox="gallery">
        </div>

        <button class="carousel-button carousel-prev" onclick="moveSlide(-1)">❮</button>
        <button class="carousel-button carousel-next" onclick="moveSlide(1)">❯</button>
    </div>

    <!-- Sección TOT MÀQUINA -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-8">
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
                <img src="/Images/Slider1_Ordenador.webp" 
                     alt="Máquina" 
                     class="w-full h-full object-cover rounded-lg">
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . "/layouts/footer.php"; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script src="/js/main.js"></script>

</body>
</html>