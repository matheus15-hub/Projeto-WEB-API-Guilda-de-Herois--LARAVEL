@extends('layouts.app')

@section('title', 'Editar ' . $classe->classe_nome . ' · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="toolbar">
        <h1>Editar classe</h1>
        <a href="{{ route('classes.index') }}">Voltar</a>
    </div>

    <form method="POST" action="{{ route('classes.update', $classe) }}">
        @method('PUT')
        @include('classes._form')
        <p><button type="submit" class="btn">Salvar alterações</button></p>
    </form>
</div>
@endsection