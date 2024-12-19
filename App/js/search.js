import $ from 'jquery';
$(document).ready(function () {
    const $searchInput = $('#searchInput');
    const $searchResults = $('#searchResults');

    $searchInput.on('input', function () {
        const query = $(this).val().trim();

        if (query.length >= 2) {
            $.ajax({
                url: '/api/search', // Cambié a una ruta absoluta para evitar problemas
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); // Verifica si obtienes datos
                    if (response.success) {
                        renderSearchResults(response.results);
                    } else {
                        $searchResults.empty().addClass('hidden');
                        alert(response.message || 'Alguna cosa ha anat malament! Si has arribat aquí vol dir que alguna cosa ha fallat.');
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en AJAX:', error);
                    $searchResults.empty().addClass('hidden');
                    alert('Alguna cosa ha anat malament! Si has arribat aquí vol dir que alguna cosa ha fallat.');
                }
            });

        } else {
            $searchResults.empty().addClass('hidden'); // Ocultar resultados si el input es vacío
        }
    });

    // Función para renderizar resultados
    function renderSearchResults(results) {
        $searchResults.empty(); // Limpiar resultados anteriores

        if (results.length === 0) {
            $searchResults.html('<p class="p-2 text-gray-600">No se encontraron resultados.</p>').removeClass('hidden');
            return;
        }

        results.forEach(function (item) {
            const resultItem = `
            <a href="/machinedetail/${item.id}" 
               class="block px-4 py-2 hover:bg-gray-100 cursor-pointer"
               data-id="${item.id}">
                <strong class="text-gray-900">${item.name} (${item.model})</strong><br>
                <span class="text-gray-600 text-sm">${item.manufacturer} - ${item.location}</span>
            </a>
        `;

            $searchResults.append(resultItem);
        });

        $searchResults.removeClass('hidden');
    }

    // Manejar clic en resultados
    $(document).on('click', '#searchResults a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        window.location.href = url; // Redirigir a la página específica de la máquina
    });

    // Ocultar resultados si se hace clic fuera
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.relative').length) {
            $searchResults.addClass('hidden');
        }
    });
});
