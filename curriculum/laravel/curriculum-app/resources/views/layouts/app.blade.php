<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CafeCafe - あなたの好きな空間を作る')</title>
    
    <!-- Favicon (Emoji) -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>☕</text></svg>">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        @yield('content')
    </main>

    @include('components.footer')

    <script src="{{ asset('js/security.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
