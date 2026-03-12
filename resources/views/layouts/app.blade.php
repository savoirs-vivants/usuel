<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @hasSection('title')
            @yield('title')
        @else
            {{ $title ?? 'Usuel' }}
        @endif
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/charts.js', 'resources/js/password-toggle.js', 'resources/js/tracking.js'])
    @livewireStyles

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>

<body class="font-grotesk bg-gray-50 text-sv-blue antialiased">

    @if (
        !Route::is('welcome') &&
            !Route::is('login') &&
            !Route::is('inscription') &&
            !Route::is('questionnaire.run') &&
            !Route::is('questions.gestion') &&
            !Route::is('password.forgot') &&
            !Route::is('password.reset') &&
            !Route::is('confidentialite') &&
            !Route::is('mentions') &&
            !Route::is('questionnaire.result'))
        <div>
            @include('components.sidebar')
            <main>
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    @else
        <main>
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    @endif

    @livewireScripts
    @include('components.toast-notification')

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 600,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50,
                delay: 0
            });
        });
    </script>
</body>

</html>
