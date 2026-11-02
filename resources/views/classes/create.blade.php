@extends('layouts.app')

@section('title', 'Nova classe · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="toolbar">
        <h1>Nova classe</h1>
        <a href="{{ route('classes.index') }}">Voltar</a>
    </div>

    <form method="POST" action="{{ route('classes.store') }}">
        @include('classes._form')
        <p><button type="submit" class="btn">Cadastrar</button></p>
    </form>
</div>
@endsection