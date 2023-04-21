<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="md:bg-gray-softest">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Objekt forscher</title>
</head>

<body>
    <div id="app" class="lg:max-w-lg lg:mx-auto lg:my-auto bg-white min-h-screen flex flex-col"></div>
    <script src="{{ mix('/js/app.js') }}"></script>
</body>

</html>
