@extends('layouts.app')

@section('title', 'Entrar · Guilda de Heróis')

@section('content')
<div class="auth">
    <aside class="auth-side">
        <h1>Guilda dos Heróis</h1>
        <p>Registre aventureiros e acompanhe as missões da sua guilda</p>
    </aside>

    <main class="auth-main">
        <div class="auth-card">
            <h2>Entrar</h2>
            <p class="sub">Acesso Exclusivo para secretários da Guilda.</p>

            <form method="POST" action="{{ route('login') }}" novalidate>
                @csrf

            <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" autocomplete="current-password" required>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <label class="check">
                <input type="checkbox" name="remember" value="1"> Manter conectado
            </label>

            <button type="submit" class="btn btn-block">Entrar</button>

            </form>

            <p class="switch">Ainda não tem conta? <a href="{{ route('register') }}">Cadastre-se</a></p>
            
            </form>
        </div>
    </main>
</div>