<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Mantenimiento</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="bg-gray-100 p-6">
  <div class="container mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Historial de Mantenimiento</h1>
    
    <?php if (empty($historial)): ?>
      <p class="text-gray-600">No hay registros de mantenimiento para esta máquina.</p>
    <?php else: ?>
      <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
            <th class="py-3 px-4 text-left border-b">Fecha</th>
            <th class="py-3 px-4 text-left border-b">Tipo</th>
            <th class="py-3 px-4 text-left border-b">Fallo</th>
            <th class="py-3 px-4 text-left border-b">Reparación</th>
            <th class="py-3 px-4 text-left border-b">Técnicos Implicados</th>
            <th class="py-3 px-4 text-left border-b">Tiempo Invertido</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($historial as $registro): ?>
            <tr class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b"><?= htmlspecialchars($registro['fecha']) ?></td>
            <td class="py-2 px-4 border-b"><?= htmlspecialchars($registro['tipo']) ?></td>
              <td class="py-2 px-4 border-b"><?= htmlspecialchars($registro['fallo']) ?></td>
              <td class="py-2 px-4 border-b"><?= htmlspecialchars($registro['reparacion']) ?></td>
              <td class="py-2 px-4 border-b"><?= htmlspecialchars(implode(", ", $registro['tecnicos'])) ?></td>
              <td class="py-2 px-4 border-b"><?= htmlspecialchars($registro['tiempo']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="mt-4">
      <a href="/maintenance" class="text-blue-500 hover:underline">Volver a seleccionar máquina</a>
    </div>
  </div>
</body>
</html>
