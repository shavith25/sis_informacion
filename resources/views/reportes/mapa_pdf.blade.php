<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa de la Zona</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        img {
            max-width: 100%;
            height: auto;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>Zona Detallada: {{ $zona->nombre }}</h1>
    <img src="{{ $mapImageUrl }}" alt="Mapa de la Zona">
</body>
</html>
