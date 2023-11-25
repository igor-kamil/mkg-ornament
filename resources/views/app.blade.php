<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overflow-hidden h-full">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Ornament explorer</title>
</head>

<body class="h-full">
    <div id="app" class="bg-black"></div>
</body>

</html>
