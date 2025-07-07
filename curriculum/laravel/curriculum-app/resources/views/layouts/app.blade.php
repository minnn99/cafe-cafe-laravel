<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CafeCafe - あなたの好きな空間を作る')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('components.header')

    <main class="main-content">
        @yield('content')
    </main>

    @include('components.footer')

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
