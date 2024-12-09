<?php 
$viewsPath = __DIR__;
include $viewsPath . "/layouts/navbar.php";
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Configuración de Perfil</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" id="errorMessage">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" id="successMessage">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Sección de foto de perfil -->
        <div class="mb-8 border-b pb-6">
            <h3 class="text-lg font-semibold mb-4">Foto de Perfil</h3>
            <div class="flex items-center space-x-6">
                <div class="shrink-0" id="avatarContainer">
                    <?php if (isset($_SESSION["user"]["avatar"]) && $_SESSION["user"]["avatar"]): ?>
                        <img class="h-16 w-16 object-cover rounded-full" 
                             src="/Images/<?= htmlspecialchars($_SESSION["user"]["avatar"]); ?>" 
                             alt="Foto de perfil actual"
                             id="avatarImage">
                    <?php else: ?>
                        <div class="h-16 w-16 rounded-full bg-gray-600 flex items-center justify-center" id="defaultAvatar">
                            <span class="text-white text-xl font-medium">
                                <?= isset($_SESSION["user"]["name"]) ? strtoupper(substr($_SESSION["user"]["name"], 0, 1)) : 'U' ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <form id="avatarForm" class="flex-1">
                    <label class="block">
                        <span class="sr-only">Elegir foto de perfil</span>
                        <input type="file" 
                               name="avatar" 
                               id="avatarInput"
                               accept="image/*"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      cursor-pointer">
                    </label>
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG o GIF (Máx. 2MB)</p>
                    <button type="submit" 
                            class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Actualizar foto
                    </button>
                </form>
            </div>
        </div>

        <!-- Formulario de información personal -->
        <form action="/update-profile" method="post" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="<?= htmlspecialchars($_SESSION["user"]["name"]) ?>"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="<?= htmlspecialchars($_SESSION["user"]["email"]) ?>"
                       disabled
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50">
                <p class="mt-1 text-sm text-gray-500">El email no se puede modificar</p>
            </div>

            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña actual</label>
                <input type="password" 
                       name="current_password" 
                       id="current_password"
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
                <input type="password" 
                       name="new_password" 
                       id="new_password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <p class="mt-1 text-sm text-gray-500">Dejar en blanco si no desea cambiar la contraseña</p>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmar nueva contraseña</label>
                <input type="password" 
                       name="confirm_password" 
                       id="confirm_password"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg
                               hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

<?php include $viewsPath . "/layouts/footer.php"; ?> 

<script>
document.getElementById('avatarForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Verificar si se seleccionó un archivo
    const fileInput = document.getElementById('avatarInput');
    if (!fileInput.files || !fileInput.files[0]) {
        showMessage('error', 'Por favor selecciona una imagen');
        return;
    }

    const formData = new FormData();
    formData.append('avatar', fileInput.files[0]);

    try {
        const response = await fetch('/update-avatar', {
            method: 'POST',
            body: formData
        });

        let data;
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            throw new Error('La respuesta del servidor no es JSON válido');
        }

        if (data.success) {
            updateAvatar(data.avatar);
            showMessage('success', data.message || 'Foto de perfil actualizada con éxito');
        } else {
            showMessage('error', data.error || 'Error al actualizar la foto de perfil');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('error', 'Error al procesar la solicitud. Por favor, intenta de nuevo.');
    }
});

// Función para mostrar mensajes
function showMessage(type, message) {
    const messageDiv = document.createElement('div');
    messageDiv.className = type === 'success' 
        ? 'mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50'
        : 'mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50';
    messageDiv.textContent = message;

    // Eliminar mensajes existentes
    const existingSuccess = document.getElementById('successMessage');
    const existingError = document.getElementById('errorMessage');
    if (existingSuccess) existingSuccess.remove();
    if (existingError) existingError.remove();

    // Insertar nuevo mensaje
    document.querySelector('h2').insertAdjacentElement('afterend', messageDiv);
    
    // Eliminar mensaje después de 3 segundos
    setTimeout(() => messageDiv.remove(), 3000);
}

// Función para actualizar el avatar
function updateAvatar(avatarFileName) {
    const avatarContainer = document.getElementById('avatarContainer');
    const defaultAvatar = document.getElementById('defaultAvatar');
    const existingImage = document.getElementById('avatarImage');

    if (avatarFileName) {
        const newImage = `<img class="h-16 w-16 object-cover rounded-full" 
                             src="/Images/${avatarFileName}?t=${Date.now()}" 
                             alt="Foto de perfil actual"
                             id="avatarImage">`;
        
        if (existingImage) {
            existingImage.src = `/Images/${avatarFileName}?t=${Date.now()}`;
        } else if (defaultAvatar) {
            avatarContainer.innerHTML = newImage;
        }
    }
}
</script> 