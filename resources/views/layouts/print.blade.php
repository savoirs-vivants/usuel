<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Document')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/certificat.js'])
    @livewireStyles

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Space+Mono:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
</head>

<body class="bg-gray-200 print:bg-white min-h-screen flex justify-center items-start p-8 font-sans">
    @yield('content')
</body>
</html>
