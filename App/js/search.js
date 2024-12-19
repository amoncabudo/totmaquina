import $ from 'jquery';
$(document).ready(function () { //Run the code when the document is ready
    const $searchInput = $('#searchInput'); //Get the search input
    const $searchResults = $('#searchResults'); //Get the search results

    $searchInput.on('input', function () {
        const query = $(this).val().trim();

        if (query.length >= 2) { //If the query is longer than 2 characters
            $.ajax({
                url: '/api/search', //Change to an absolute path to avoid problems
                method: 'GET',
                data: { query: query },
                success: function (response) {
                    console.log('Respuesta del servidor:', response); //Check if you get data
                    if (response.success) {
                        renderSearchResults(response.results);
                    } else {
                        $searchResults.empty().addClass('hidden'); //Hide the search results
                        alert(response.message || 'Alguna cosa ha anat malament! Si has arribat aquí vol dir que alguna cosa ha fallat.'); //Show an alert
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error en AJAX:', error); //Check if there is an error
                    $searchResults.empty().addClass('hidden'); //Hide the search results
                    alert('Alguna cosa ha anat malament! Si has arribat aquí vol dir que alguna cosa ha fallat.'); //Show an alert
                }
            });

        } else {
            $searchResults.empty().addClass('hidden'); //Hide the search results if the input is empty
        }
    });

    function renderSearchResults(results) {
        $searchResults.empty(); //Clear the previous results

        if (results.length === 0) {
            $searchResults.html('<p class="p-2 text-gray-600">No se encontraron resultados.</p>').removeClass('hidden'); //Show a message if no results are found
            return;
        }

        results.forEach(function (item) { //For each result, create a link
            const resultItem = `
            <a href="http://localhost/machine_detail/${item.id}" 
               class="block px-4 py-2 hover:bg-gray-100 cursor-pointer"
               data-id="${item.id}">
                <strong class="text-gray-900">${item.name} (${item.model})</strong><br> 
                <span class="text-gray-600 text-sm">${item.manufacturer} - ${item.location}</span>
            </a>
        `;

            $searchResults.append(resultItem); //Add the result to the search results
        });

        $searchResults.removeClass('hidden');
    }

    $(document).on('click', '#searchResults a', function (e) { //Handle click on results
        e.preventDefault();
        const url = $(this).attr('href');
        window.location.href = url; //Redirect to the specific machine page
    });

    $(document).on('click', function (e) { //Handle click outside the search results
        if (!$(e.target).closest('.relative').length) {
            $searchResults.addClass('hidden'); //Hide the search results if the click is outside
        }
    });
});
