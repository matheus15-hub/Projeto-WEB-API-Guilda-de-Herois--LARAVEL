<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Guilda de Heróis')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,500;0,700;0,800;1,500&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/guilda.css') }}">
</head>
<body>
    @auth
        <header class="topbar">
            <a class="brand" href="{{ route('dashboard') }}">Guilda dos Heróis</a>
            <div class="who">
                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <button type="submit" class="btn btn-ghost">Sair</button>
                </form>
            </div>
        </header>

    @endauth

    @yield('content')
</body>
</html>