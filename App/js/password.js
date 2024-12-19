import $ from "jquery";

document.addEventListener('DOMContentLoaded', function () {
    initializePasswordValidation();
});

// Password validation
function initializePasswordValidation() {
    // Individual patterns for each requirement
    const patterns = {
        minLength: /.{6,13}/,
        lowercase: /[a-z]/,
        uppercase: /[A-Z]/,
        number: /\d/,
        special: /[$@$!%*?&-.,]/,
        noSpaces: /^[^\s']+$/
    };

    function validatePassword(password) {
        return {
            minLength: patterns.minLength.test(password),
            lowercase: patterns.lowercase.test(password),
            uppercase: patterns.uppercase.test(password),
            number: patterns.number.test(password),
            special: patterns.special.test(password),
            noSpaces: patterns.noSpaces.test(password)
        };
    }

    function updatePasswordFeedback(results, messageContainer) {
        const messages = {
            minLength: 'Entre 6 y 13 caracteres',
            lowercase: 'Al menos una minúscula',
            uppercase: 'Al menos una mayúscula',
            number: 'Al menos un número',
            special: 'Al menos un carácter especial ($@!%*?&-.,)',
            noSpaces: 'Sin espacios ni comillas simples'
        };

        let html = '<ul class="text-sm mt-2">';
        for (const [requirement, passed] of Object.entries(results)) {
            const color = passed ? 'text-green-600' : 'text-red-600';
            const icon = passed ? '✔' : '✗';
            html += `<li class="${color}"><span class="mr-2">${icon}</span>${messages[requirement]}</li>`;
        }
        html += '</ul>';

        messageContainer.html(html);

        // Check if all requirements are met
        const allPassed = Object.values(results).every(result => result);
        return allPassed;
    }

    function handlePasswordValidation() {
        const password = $(this).val();
        const results = validatePassword(password);
        const messageContainer = $(this).siblings('.password-requirements');

        // Create the requirements container if it doesn't exist
        if (messageContainer.length === 0) {
            $(this).after('<div class="password-requirements"></div>');
        }

        const allPassed = updatePasswordFeedback(results, $(this).siblings('.password-requirements'));

        // Update the input style using Tailwind classes
        $(this)
            .removeClass("border-red-500 border-green-500") // Elimina clases previas
            .addClass(allPassed ? "border-green-500" : "border-red-500"); // Añade clase según resultado

        // Habilitar o deshabilitar el botón
        $("#btnEnviar").prop("disabled", !allPassed);
    }

    function handleEditPasswordValidation() {
        const password = $(this).val();
        const messageContainer = $(this).siblings('.password-requirements');

        // Crear el contenedor de requisitos si no existe
        if (messageContainer.length === 0) {
            $(this).after('<div class="password-requirements"></div>');
        }

        // Si el campo está vacío en modo edición
        if (password === "") {
            $(this).removeClass("border-red-500 border-green-500");
            messageContainer.html("");
            $("button[type='submit']").prop("disabled", false);
            return;
        }

        const results = validatePassword(password);
        const allPassed = updatePasswordFeedback(results, messageContainer);

        // Actualizar el estilo del input usando clases de Tailwind
        $(this)
            .removeClass("border-red-500 border-green-500")
            .addClass(allPassed ? "border-green-500" : "border-red-500");

        $("button[type='submit']").prop("disabled", !allPassed);
    }

    // Asignar eventos
    $('#password').on('keyup', handlePasswordValidation);
    $('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation);
}
