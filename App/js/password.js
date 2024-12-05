

console.log("password.js loaded");
console.log("messi");

// La expresión regular para validar la contraseña 
//(debe contener al menos una minúscula, una mayúscula, un número, un carácter especial y tener una longitud de entre 6 y 13 caracteres)
var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&-.,])[A-Za-z\d$@$!%*?&-.,]{6,13}[^'\s]/;

// Función para manejar la validación de la contraseña en el formulario de creación
function handlePasswordValidation() {
    var password = $(this).val();
    console.log(password);
    
    if (pattern.test(password)) {
        $(this).css("border", "2px solid green");
        $("#mss").html("Contraseña válida").css("color", "green");
        $("#btnEnviar").prop("disabled", false);
    } else {
        $(this).css("border", "2px solid red");
        $("#mss").html("Contraseña inválida").css("color", "red");
        $("#btnEnviar").prop("disabled", true);
    }
}

// Función para manejar la validación de la contraseña en el formulario de edición
function handleEditPasswordValidation() {
    var password = $(this).val();
    console.log(password);
    
    // Si el campo está vacío, permitir el envío (password opcional en edición)
    if (password === "") {
        $(this).css("border", "");
        $("#edit-mss").html("").css("display", "none");
        $("button[type='submit']").prop("disabled", false);
        return;
    }
    
    if (pattern.test(password)) {
        $(this).css("border", "2px solid green");
        $("#edit-mss").html("Contraseña válida").css({
            "color": "green",
            "display": "block"
        });
        $("button[type='submit']").prop("disabled", false);
    } else {
        $(this).css("border", "2px solid red");
        $("#edit-mss").html("Contraseña inválida").css({
            "color": "red",
            "display": "block"
        });
        $("button[type='submit']").prop("disabled", true);
    }
}

// Asignar eventos
$('#password').on('keyup', handlePasswordValidation);
$('input[id^="edit-password-"]').on('keyup', handleEditPasswordValidation);

