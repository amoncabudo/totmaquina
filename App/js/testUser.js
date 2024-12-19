import $ from "jquery";

document.addEventListener('DOMContentLoaded', function() {
    initializeUserManagement();
});

// Function to generate test users
function initializeUserManagement() {
    $(document).ready(function() {
        console.log('DOM loaded, setting up event listeners...');
    
        $('#createTestTechnician').on('click', function() {
            generarUsuarioPrueba('technician');
        });
    
        $('#createTestSupervisor').on('click', function() {
            generarUsuarioPrueba('supervisor');
        });
    });
}

function generarUsuarioPrueba(role) {
    console.log('Generating test user for role:', role);

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

            // Send to server
            $.ajax({
                url: '/createTestUser',
                method: 'POST',
                data: usuarioPrueba,
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    console.log('Server response:', response);
                    const result = typeof response === 'string' ? JSON.parse(response) : response;
                    if (result.success) {
                        window.location.reload();
                    } else {
                        console.error('Server error:', result.message);
                        alert('Error: ' + result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Request error:', error);
                    alert('Error creating user: ' + error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error fetching random user:', error);
            alert('Error generating test user: ' + error);
        }
    });
}