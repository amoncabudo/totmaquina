$(document).ready(function() {
    console.log('DOM cargado, configurando event listeners...');

    $('#createTestTechnician').on('click', function() {
        generarUsuarioPrueba('technician');
    });

    $('#createTestSupervisor').on('click', function() {
        generarUsuarioPrueba('supervisor');
    });
});

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
                        const usuarioCompleto = result.user || {...usuarioPrueba, id: result.userId};
                        agregarUsuarioAlDOM(usuarioCompleto);
                        alert('Usuario de prueba creado con éxito');
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

function agregarUsuarioAlDOM(usuario) {
    const tabla = $('#usuariosTabla tbody');
    if (!tabla.length) {
        console.error('No se encontró la tabla en el DOM');
        return;
    }

    console.log('Agregando usuario al DOM:', usuario);

    if (!usuario.id) {
        console.error('El usuario no tiene ID:', usuario);
        return;
    }

    const nuevaFila = $(`
        <tr>
            <td>${usuario.nombre}</td>
            <td>${usuario.apellido}</td>
            <td>${usuario.email}</td>
            <td>${usuario.rol}</td>
            <td>
                <button class="btn btn-sm btn-primary edit-user" data-id="${usuario.id}">Editar</button>
                <button class="btn btn-sm btn-danger delete-user" data-id="${usuario.id}">Eliminar</button>
            </td>
        </tr>
    `);

    tabla.prepend(nuevaFila);
    console.log('Usuario agregado exitosamente al DOM');
}
