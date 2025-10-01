<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Agent</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-sidebar/>
    {{ $slot }}
</body>
</html>