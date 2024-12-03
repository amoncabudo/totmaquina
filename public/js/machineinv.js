$(document).ready(function () {
    var dropZone = $('#drop-zone');

    // Cuando se arrastra un archivo sobre la zona de drop se añade la clase dragover
    dropZone.on('dragover', function (e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    // Cuando se sale de la zona de drop se elimina la clase dragover
    dropZone.on('dragleave', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    // Cuando se suelta el archivo en la zona de drop se ejecuta la función handleFiles
    dropZone.on('drop', function (e) {
        e.preventDefault();
        $(this).removeClass('dragover');

        var files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    // Cambia el selector para incluir dropzone-file dentro de drop-zone
    $(document).on('change', '#dropzone-file', function () {
        var files = $(this)[0].files;
        handleFiles(files);
    });

    // Función para manejar los archivos
    function handleFiles(files) {
        for (var i = 0; i < files.length; i++) {
            uploadFile(files[i]);
        }
    }

    // Función para subir el archivo al servidor
    function uploadFile(file) {
        var formData = new FormData();
        formData.append('file', file);
        var usuarioID = $('#idUsuario').text();
        console.log(usuarioID);
        formData.append('usuarioID', usuarioID);

        $.ajax({
            url: '/fotoalumno1',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                alert('Foto subida correctamente');
            },
            error: function (error) {
                console.error('Error fotoalumno file:', error);
            }
        });
    }
});


