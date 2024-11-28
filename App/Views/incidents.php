<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestió d'Incidències</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="css/main.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include __DIR__ . "/layouts/navbar.php"; ?>
</head>
<body class="bg-gray-100 p-6">

  <!-- Header -->
  <header class="bg-blue-600 text-white py-4 shadow-md">
    <h1 class="text-center text-2xl font-bold">Gestió de incidències</h1>
  </header>

  <!-- Main Content -->
  <main class="max-w-5xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Registrar incidència</h2>
      <form>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Nom del dispositiu -->
          <div>
            <label for="device" class="block text-sm font-medium text-gray-700">Dispositivo</label>
            <input type="text" id="device" name="device" placeholder="Nom del dispositiu" 
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
          
          <!-- Descripció -->
          <div>
            <label for="issue" class="block text-sm font-medium text-gray-700">Descripcion de la averia</label>
            <textarea id="issue" name="issue" rows="2" placeholder="Descripció breu de l'incidència"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
          </div>

          <!-- Prioritat -->
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Prioridad</label>
            <select id="priority" name="priority" 
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <option value="baixa">Baja</option>
              <option value="mitjana">Mediana</option>
              <option value="alta">Alta</option>
            </select>
          </div>

          <!-- Selecció de tècnics -->
          <div>
            <label for="technicians" class="block text-sm font-medium text-gray-700">Assignar Tècnicos</label>
            <div x-data="{ 
              open: false, 
              selectedTechnicians: [], 
              technicians: ['Maria López', 'Joan Garcia', 'Anna Puig', 'Pere Roca', 'Clara Vidal', 'Marc Soler'] 
            }" 
                 class="relative">
              <!-- Botó principal -->
              <button @click.prevent="open = !open" type="button" 
                class="w-full border border-gray-300 bg-white rounded-md shadow-sm px-4 py-2 text-left focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <template x-if="selectedTechnicians.length === 0">
                  <span>Selecciona tècnicos</span>
                </template>
                <template x-if="selectedTechnicians.length > 0">
                  <span x-text="selectedTechnicians.join(', ')"></span>
                </template>
              </button>
              

              <!-- Dropdown amb scrolling -->
              <div x-show="open" x-cloak
                   class="absolute z-10 mt-2 w-full bg-white shadow-lg max-h-60 overflow-y-auto rounded-md border border-gray-200" 
                   @click.away="open = false">
                <ul class="py-1">
                  <template x-for="tech in technicians" :key="tech">
                    <li>
                      <label class="flex items-center px-4 py-2 cursor-pointer hover:bg-gray-100">
                        <input type="checkbox" :value="tech" class="form-checkbox" 
                          @change="event.target.checked ? selectedTechnicians.push(tech) : selectedTechnicians.splice(selectedTechnicians.indexOf(tech), 1)">
                        <span class="ml-2 text-sm" x-text="tech"></span>
                      </label>
                    </li>
                  </template>
                </ul>
              </div>
            </div>
          </div>

          <!-- Hores estimades -->
          <div>
            <label for="hours" class="block text-sm font-medium text-gray-700">Hores Estimades</label>
            <input type="number" id="hours" name="hours" placeholder="Hores" 
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>

          <!-- Data -->
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Data de Registr</label>
            <input type="date" id="date" name="date" 
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
        </div>

        <!-- Botons -->
        <div class="mt-4 flex items-center space-x-4">
          <button type="submit" 
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Registrar Incidència
          </button>
          <button type="button" @click.prevent="$refs.form.reset(); selectedTechnicians = [];" 
            class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
            Netejar
          </button>
        </div>
      </form>
    </div>

    <!-- Últimas incidencias no resueltas -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Últimas incidencias no resueltas</h2>

      <!-- Lista de incidencias -->
      <ul class="space-y-4">
        <!-- Incidencia 1 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidencia 1: Fallo en el dispositivo X</h3>
            <p class="text-sm text-gray-600">Descripción: Pantalla en negro</p>
          </div>
          <div class="flex items-center">
            <!-- Termómetro Horizontal -->
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-red-600 rounded-full" style="width: 100%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Alta</span>
          </div>
        </li>

        <!-- Incidencia 2 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidencia 2: Fallo en el dispositivo Y</h3>
            <p class="text-sm text-gray-600">Descripción: Error al iniciar la aplicación</p>
          </div>
          <div class="flex items-center">
            <!-- Termómetro Horizontal -->
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-yellow-500 rounded-full" style="width: 60%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Mediana</span>
          </div>
        </li>

        <!-- Incidencia 3 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidencia 3: Fallo en el dispositivo Z</h3>
            <p class="text-sm text-gray-600">Descripción: No conecta a internet</p>
          </div>
          <div class="flex items-center">
            <!-- Termómetro Horizontal -->
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-green-500 rounded-full" style="width: 25%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Baja</span>
          </div>
        </li>
      </ul>
    </div>

    <!-- Estadísticas -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Estadísticas de incidencias</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Gráfico de incidencias por dispositivo -->
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Incidencias por Dispositivo</h3>
          <canvas id="deviceChart"></canvas>
        </div>

        <!-- Gráfico de incidencias por mes -->
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Incidencias por Mes</h3>
          <canvas id="monthlyChart"></canvas>
        </div>

        <!-- Gráfico de tiempos de respuesta -->
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Tiempo de Respuesta</h3>
          <canvas id="responseChart"></canvas>
        </div>
      </div>
    </div>

  </main>

  <script>
    const deviceChartCtx = document.getElementById('deviceChart').getContext('2d');
    const monthlyChartCtx = document.getElementById('monthlyChart').getContext('2d');
    const responseChartCtx = document.getElementById('responseChart').getContext('2d');

    // Chart.js for device incidents
    const deviceChart = new Chart(deviceChartCtx, {
      type: 'pie',
      data: {
        labels: ['Dispositiu A', 'Dispositiu B', 'Dispositiu C', 'Dispositiu D'],
        datasets: [{
          data: [12, 19, 6, 3],
          backgroundColor: ['#FF9999', '#66B2FF', '#99FF99', '#FFCC99'],
        }]
      },
      options: {
        responsive: true,
      }
    });

    // Chart.js for monthly incidents
    const monthlyChart = new Chart(monthlyChartCtx, {
      type: 'bar',
      data: {
        labels: ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'],
        datasets: [{
          label: 'Incidències',
          data: [15, 20, 25, 18, 22, 17, 19, 23, 20, 21, 18, 20],
          backgroundColor: '#4CAF50',
        }]
      },
      options: {
        responsive: true,
      }
    });

    // Chart.js for response time
    const responseChart = new Chart(responseChartCtx, {
      type: 'line',
      data: {
        labels: ['Día 1', 'Día 2', 'Día 3', 'Día 4', 'Día 5'],
        datasets: [{
          label: 'Tiempo de Respuesta (h)',
          data: [5, 6, 4, 7, 3],
          fill: false,
          borderColor: '#FF6347',
          tension: 0.1,
        }]
      },
      options: {
        responsive: true,
      }
    });
  </script>

</body>
</html>
