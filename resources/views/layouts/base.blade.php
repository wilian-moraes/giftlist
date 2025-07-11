<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - GiftList</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @vite('resources/css/app.css')
</head>
<body x-data="{ isBodyScrollLocked: false}" :class="{'overflow-hidden': isBodyScrollLocked}">
    <main>
        @yield('content')
    </main>

    @vite(['resources/js/app.js'])
</body>
</html>
