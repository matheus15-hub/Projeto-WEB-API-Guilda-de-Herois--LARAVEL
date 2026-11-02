@extends('layouts.app')

@section('title', 'Classes · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="toolbar">
        <h1>Classes</h1>
        <a href="{{ route('classes.create') }}" class="btn">+ Nova classe</a>
    </div>

    @include('partials.flash')

    @if ($classes->isEmpty())
        <div class="notice">Nenhuma classe cadastrada ainda.</div>
    @else
        <div class="table-wrap">
            <table class="roster">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Raridade</th>
                        <th>Descrição</th>
                        <th class="num">Aventureiros</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classes as $c)
                        <tr>
                            <td>{{ $c->classe_nome }}</td>
                            <td>{{ $c->raridade }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($c->descricao, 80) }}</td>
                            <td class="num">{{ $c->aventureiros_count }}</td>
                            <td class="actions">
                                <a href="{{ route('classes.edit', $c) }}">Editar</a>
                                <form method="POST" action="{{ route('classes.destroy', $c) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('Excluir a classe {{ $c->classe_nome }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection