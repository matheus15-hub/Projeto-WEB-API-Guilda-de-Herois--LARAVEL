@extends('layouts.app')

@section('title', 'Cadastro · Guilda de Heróis')

@section('content')
<div class="auth">
    <aside class="auth-side">
        <h1>Guilda de Heróis</h1>
        <p>Cada secretário gerencia apenas a guilda à qual está vinculado.</p>
    </aside>

    <main class="auth-main">
        <div class="auth-card">
            <h2>Criar conta</h2>
            <p class="sub">Escolha a guilda que você vai administrar.</p>

            <form method="POST" action="{{ route('registro') }}" novalidate>
                @csrf

                <div class="field {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">Nome</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete="name" autofocus required>
                    @error('name') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                    @error('email') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field {{ $errors->has('id_guilda') ? 'has-error' : '' }}">
                    <label for="id_guilda">Guilda</label>
                    <select id="id_guilda" name="id_guilda" required>
                        <option value="">Selecione…</option>
                        @foreach ($guildas as $guilda)
                            <option value="{{ $guilda->guilda_id }}" @selected(old('id_guilda') == $guilda->guilda_id)>
                                {{ $guilda->guilda_nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_guilda') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password">Senha</label>
                    <input id="password" type="password" name="password" autocomplete="new-password" required>
                    @error('password') <p class="field-error">{{ $message }}</p> @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-block">Criar conta</button>
            </form>

            <p class="switch">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
        </div>
    </main>
</div>
@endsection