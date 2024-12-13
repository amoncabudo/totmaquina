import $ from "jquery";
   document.addEventListener('DOMContentLoaded', function() {
    initializePasswordValidation();
   });

   // Validación de contraseña
   function initializePasswordValidation() {
    // Patrones individuales para cada requisito
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

        // Verificar si todos los requisitos se cumplen
        const allPassed = Object.values(results).every(result => result);
        return allPassed;
    }

    function handlePasswordValidation() {
        const password = $(this).val();
        const results = validatePassword(password);
        const messageContainer = $(this).siblings('.password-requirements');
        
        // Crear el contenedor de requisitos si no existe
        if (messageContainer.length === 0) {
            $(this).after('<div class="password-requirements"></div>');
        }
        
        const allPassed = updatePasswordFeedback(results, $(this).siblings('.password-requirements'));
        
        // Actualizar el estilo del input y el estado del botón
        if (allPassed) {
            $(this).css("border", "2px solid green");
            $("#btnEnviar").prop("disabled", false);
        } else {
            $(this).css("border", "2px solid red");
            $("#btnEnviar").prop("disabled", true);
        }
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
            $(this).css("border", "");
            messageContainer.html("");
            $("button[type='submit']").prop("disabled", false);
            return;
        }
        
        const results = validatePassword(password);
        const allPassed = updatePasswordFeedback(results, messageContainer);
        
        // Actualizar el estilo del input y el estado del botón
        if (allPassed) {
            $(this).css("border", "2px solid green");
            $("button[type='submit']").prop("disabled", false);
        } else {
            $(this).css("border", "2px solid red");
            $("button[type='submit']").prop("disabled", true);
        }
    }

    // Asignar eventos
    $('#password').on('keyup', handlePasswordValidation);
    $('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation);
}