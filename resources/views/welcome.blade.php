<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Guilda de Heróis · Bem-vindo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alegreya:wght@700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/guilda.css') }}">
</head>
<body>
<main class="auth">

    <section class="auth-side">
        <h1>Guilda de Heróis</h1>
        <p>Reúna seus aventureiros, organize missões e acompanhe a glória da guilda em um só lugar.</p>

        <ul class="feature-list">
            <li><strong>Aventureiros</strong> Cadastre e acompanhe todos os membros.</li>
            <li><strong>Missões</strong> Distribua tarefas e marque as concluídas.</li>
            <li><strong>Painel</strong> Veja o estado da guilda num relance.</li>
        </ul>
    </section>

    <section class="auth-main">
        <div class="auth-card">
            @auth
                <h2>Bem-vindo de volta</h2>
                <p class="sub">Olá, {{ auth()->user()->name }}. Seus portões estão abertos.</p>

                {{-- AJUSTE o nome da rota do painel --}}
                <a href="{{ route('dashboard') }}" class="btn btn-block">Entrar no painel</a>
            @else
                <h2>Cruze os portões</h2>
                <p class="sub">Entre na sua conta ou junte-se à guilda.</p>

                <a href="{{ route('login') }}" class="btn btn-block">Entrar</a>
                <a href="{{ route('register') }}" class="btn btn-ghost btn-block">Criar conta</a>
            @endauth
        </div>
    </section>

</main>
</body>
</html>