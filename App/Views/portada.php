<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Lista de Usuarios</title>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Lista de Usuarios</h1>
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
        <thead>
          <tr class="bg-gray-800 text-white">
            <th class="py-3 px-6 text-left font-semibold text-sm">ID</th>
            <th class="py-3 px-6 text-left font-semibold text-sm">Nombre</th>
            <th class="py-3 px-6 text-left font-semibold text-sm">Apellido</th>
            <th class="py-3 px-6 text-left font-semibold text-sm">Correo</th>
            <th class="py-3 px-6 text-left font-semibold text-sm">Rol</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr class="border-b border-gray-200 hover:bg-gray-100">
              <td class="py-3 px-6"><?= htmlspecialchars($user['id']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($user['name']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($user['surname']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($user['email']) ?></td>
              <td class="py-3 px-6"><?= htmlspecialchars($user['role']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <p class="mt-4 text-center text-gray-600"><?= htmlspecialchars($missatge) ?></p>
  </div>

  <script src="/js/bundle.js"></script>
  <script src="/js/flowbite.min.js"></script>

</body>
</html>
