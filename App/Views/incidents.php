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
      <form method="POST" action="ctrladdincidents.php">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          
          <!-- Seleccionar màquina -->
          <div>
            <label for="machine_id" class="block text-sm font-medium text-gray-700">Selecciona una maquina 🏗</label>
            <select name="machine_id" id="machine_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Selecciona una maquina</option>
                <?php foreach ($machines as $machine): ?>
                    <option value="<?php echo $machine['id']; ?>"><?php echo htmlspecialchars($machine['name']); ?><br> <?php echo htmlspecialchars($machine['model']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <!-- Descripció -->
          <div>
            <label for="issue" class="block text-sm font-medium text-gray-700">Descripcion de la averia📋</label>
            <textarea id="issue" name="issue" rows="2" placeholder="Descripcion breve de la averia" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
          </div>

          <!-- Prioritat -->
          <div>
            <label for="priority" class="block text-sm font-medium text-gray-700">Prioridad⚠️</label>
            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
              <option value="baixa">Baja</option>
              <option value="mitjana">Mediana</option>
              <option value="alta">Alta</option>
            </select>
          </div>

          <!-- Seleccionar tècnic -->
          <div>
            <label for="technician_id" class="block text-sm font-medium text-gray-700">Selecciona un tecnico👨🏽‍🔧</label>
            <select name="technician_id" id="technician_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">Selecciona un tecnico</option>
                <?php foreach ($technicians as $technician): ?>
                    <option value="<?php echo $technician['id']; ?>"><?php echo htmlspecialchars($technician['name']); ?> <br><?php echo htmlspecialchars($technician['surname']); ?></option>
                <?php endforeach; ?>
            </select>
          </div>

          <!-- Hores estimades -->
          <div>
            <label for="hours" class="block text-sm font-medium text-gray-700">Hores Estimadas⌛️</label>
            <input type="number" id="hours" name="hours" placeholder="Hores" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>

          <!-- Data -->
          <div>
            <label for="date" class="block text-sm font-medium text-gray-700">Data de registro📆</label>
            <input type="date" id="date" name="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          </div>
        </div>

        <!-- Botons -->
        <div class="mt-4 flex items-center justify-between space-x-4">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Registrar Incidencia💾</button>
          <button type="button" @click.prevent="$refs.form.reset(); selectedTechnicians = [];" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Limpiar🧹</button>
        </div>
      </form>
    </div>

    <!-- Últimes incidències no resoltes -->
    <div class="bg-white shadow-lg rounded-lg p-6 mt-8">
      <h2 class="text-xl font-semibold mb-4 text-gray-700">Ultimas incidencies no resueltas</h2>
      <ul class="space-y-4">
        <!-- Lista de incidencias -->
      </ul>
    </div>
  </main>
</body>
</html>
