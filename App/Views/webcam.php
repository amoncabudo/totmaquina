<!DOCTYPE html>
<html lang="es">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Webcam</title>
</head>
<body>
    <video id="video" width="320" height="240" autoplay></video>
    <button id="take-photo">Tomar Foto</button>
    <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
    <img id="photo" alt="Foto capturada" style="display:none;"/>

    <script src="/js/bundle.js"></script>
</body>
</html>