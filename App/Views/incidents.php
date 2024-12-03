<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestió d'Incidències</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.0/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
  <link rel="stylesheet" href="css/main.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <?php include __DIR__ . "/layouts/navbar.php"; ?>
</head>
<body class="bg-gray-100 p-6">

  <!-- Header -->
  <header class="bg-blue-600 text-white py-4 shadow-md">
    <h1 class="text-center text-2xl font-bold">Gestió d'Incidències</h1>
  </header>

  <!-- Main Content -->
  <main class="max-w-5xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Registrar incidència</h2>
      <form method="POST" action="">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <!-- Seleccionar màquina -->
          <div>
            <label for="machine_id" class="block text-sm font-medium text-gray-700">Selecciona una màquina:</label>
            <select name="machine_id" id="machine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Selecciona una màquina</option>
                <?php foreach ($machines as $machine): ?>
                    <option value="<?php echo $machine['id']; ?>"><?php echo htmlspecialchars($machine['name']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <!-- Descripció -->
          <div>
            <label for="issue" class="block text-sm font-medium text-gray-700">Descripció de la averia📋</label>
            <textarea id="issue" name="issue" rows="2" placeholder="Descripció breu de l'incidència" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
          </div>

          <!-- Prioritat -->
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Prioritat⚠️</label>
            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <option value="baixa">Baja</option>
              <option value="mitjana">Mitjana</option>
              <option value="alta">Alta</option>
            </select>
          </div>

          <!-- Seleccionar tècnic -->
          <div>
            <label for="technician_id" class="block text-sm font-medium text-gray-700">Selecciona un tècnic:</label>
            <select name="technician_id" id="technician_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Selecciona un tècnic</option>
                <?php foreach ($technicians as $technician): ?>
                    <option value="<?php echo $technician['id']; ?>"><?php echo htmlspecialchars($technician['name']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <!-- Hores estimades -->
          <div>
            <label for="hours" class="block text-sm font-medium text-gray-700">Hores Estimades⌛️</label>
            <input type="number" id="hours" name="hours" placeholder="Hores" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>

          <!-- Data -->
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Data de registre📆</label>
            <input type="date" id="date" name="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
        </div>

        <!-- Botons -->
        <div class="mt-4 flex items-center justify-between space-x-4">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Registrar Incidència💾</button>
          <button type="button" @click.prevent="$refs.form.reset(); selectedTechnicians = [];" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Limpiar🧹</button>
        </div>
      </form>
    </div>

    <!-- Últimes incidències no resoltes -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Últimes incidències no resoltes</h2>
      <ul class="space-y-4">
        <!-- Incidència 1 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidència 1: Fallo en el dispositiu X</h3>
            <p class="text-sm text-gray-600">Descripció: Pantalla en negre</p>
          </div>
          <div class="flex items-center">
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-red-600 rounded-full" style="width: 100%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Alta</span>
          </div>
        </li>
        <!-- Incidència 2 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidència 2: Fallo en el dispositiu Y</h3>
            <p class="text-sm text-gray-600">Descripció: Error al iniciar la aplicació</p>
          </div>
          <div class="flex items-center">
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-yellow-500 rounded-full" style="width: 60%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Mitjana</span>
          </div>
        </li>
        <!-- Incidència 3 -->
        <li class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-medium text-gray-800">Incidència 3: Fallo en el dispositiu Z</h3>
            <p class="text-sm text-gray-600">Descripció: No connecta a internet</p>
          </div>
          <div class="flex items-center">
            <div class="w-32 h-8 bg-gray-200 rounded-full relative">
              <div class="absolute inset-0 bg-green-500 rounded-full" style="width: 25%;"></div>
            </div>
            <span class="ml-2 text-sm text-gray-700">Baja</span>
          </div>
        </li>
      </ul>
    </div>

    <!-- Estadístiques -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Estadístiques de incidències</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Incidències per Dispositiu</h3>
          <canvas id="deviceChart"></canvas>
        </div>
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Incidències per Mes</h3>
          <canvas id="monthlyChart"></canvas>
        </div>
        <div>
          <h3 class="text-lg font-medium text-gray-800 mb-2">Temps de Resposta</h3>
          <canvas id="responseChart"></canvas>
        </div>
      </div>
    </div>
  </main>

  <script src="/js/main.js"></script>
</body>
</html>
