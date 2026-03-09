<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Usuel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-grotesk bg-gray-50 text-sv-blue antialiased">

        @if(!Route::is('welcome') && !Route::is('login') && !Route::is('inscription') && !Route::is('questionnaire.run') && !Route::is('questions.gestion') && !Route::is('password.forgot') && !Route::is('password.reset'))
        
        <div>
            @include('components.sidebar')
            <main>
                @yield('content')
            </main>
        </div>

    @else        <main>
            @yield('content')
        </main>
    @endif

    @livewireScripts
    @include('components.toast-notification')
</body>
</html>
