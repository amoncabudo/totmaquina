 import $ from "jquery";

 document.addEventListener('DOMContentLoaded', function() {
    initializeUserManagement();
 });

 // Función para generar usuarios de prueba
 function initializeUserManagement() {
    $(document).ready(function() {
        console.log('DOM cargado, configurando event listeners...');
    
        $('#createTestTechnician').on('click', function() {
            generarUsuarioPrueba('technician');
        });
    
        $('#createTestSupervisor').on('click', function() {
            generarUsuarioPrueba('supervisor');
        });
    });
}

function generarUsuarioPrueba(role) {
    console.log('Generando usuario de prueba para el rol:', role);

    $.ajax({
        url: 'https://randomuser.me/api/?nat=es&inc=email,name,login',
        dataType: 'json',
        success: function(data) {
            const user = data.results[0];
            const usuarioPrueba = {
                nombre: user.name.first,
                apellido: user.name.last,
                email: user.email,
                pass: 'Testing10.',
                rol: role
            };

            // Enviar al servidor
            $.ajax({
                url: '/createTestUser',
                method: 'POST',
                data: usuarioPrueba,
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    const result = typeof response === 'string' ? JSON.parse(response) : response;
                    if (result.success) {
                        window.location.reload();
                    } else {
                        console.error('Error del servidor:', result.message);
                        alert('Error: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición:', error);
                    alert('Error al crear el usuario: ' + error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener usuario aleatorio:', error);
            alert('Error al generar usuario de prueba: ' + error);
        }
    });
}