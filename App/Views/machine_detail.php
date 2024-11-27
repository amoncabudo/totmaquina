<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detalle de la Máquina</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="max-w-3xl mx-auto bg-white p-8 mt-10 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">Detalle de la Máquina</h1>
    <div class="bg-gray-200 p-4 rounded-lg shadow-md">
      <h2 class="text-lg font-bold"><?= htmlspecialchars($machine['name']) ?></h2>
      <p><strong>Modelo:</strong> <?= htmlspecialchars($machine['model']) ?></p>
      <p><strong>Fabricante:</strong> <?= htmlspecialchars($machine['manufacturer']) ?></p>
      <p><strong>Ubicación:</strong> <?= htmlspecialchars($machine['ubicacion']) ?></p>
      <p><strong>Fecha de instalación:</strong> <?= htmlspecialchars($machine['i']) ?></p>
      <p><strong>Número de serie:</strong> <?= htmlspecialchars($machine['serial_number']) ?></p>
      <p><strong>Asignar a técnico:</strong> <?= htmlspecialchars($machine['tecnico']) ?></p>
    </div>
  </div>
</body>
</html> 