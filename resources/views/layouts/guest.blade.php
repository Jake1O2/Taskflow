<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TaskFlow') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Space+Grotesk:wght@300..700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden antialiased font-sans selection:bg-primary/30 selection:text-white">
    <div
        class="fixed inset-0 -z-10 overflow-hidden bg-[radial-gradient(circle_at_15%_5%,rgba(42,120,182,0.36),transparent_35%),radial-gradient(circle_at_85%_0%,rgba(13,148,136,0.3),transparent_38%),linear-gradient(145deg,#061423_0%,#0b1f35_52%,#0f2e4a_100%)]">
        <div class="absolute -left-24 top-8 h-72 w-72 rounded-full border border-white/10"></div>
        <div class="absolute right-[-4rem] top-12 h-96 w-96 rounded-full border border-cyan-300/20"></div>
        <div
            class="absolute bottom-[-7rem] left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-warning/20 blur-[130px]">
        </div>
        <div class="absolute inset-0 grain-texture"></div>
    </div>

    <div class="relative z-10 flex min-h-screen items-center justify-center p-4 sm:p-6 lg:p-10">
        <div class="w-full max-w-lg">
            @yield('content')
        </div>
    </div>
</body>

</html>