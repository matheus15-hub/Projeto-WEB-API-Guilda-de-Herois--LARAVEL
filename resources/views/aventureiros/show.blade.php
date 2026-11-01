@extends('layouts.app')

@section('title', $aventureiro->aventureiro_nome . ' · Guilda de Heróis')

@section('content')
<div class="page">
    <h1>{{ $aventureiro->aventureiro_nome }}</h1>

    @include('partials.flash')

    <p><strong>Classe:</strong> {{ $aventureiro->classe?->classe_nome ?? '—' }}</p>
    <p><strong>Idade:</strong> {{ $aventureiro->idade }}</p>
    <p><strong>Experiência:</strong> {{ $aventureiro->experiencia }}</p>
    <p><strong>Ouro:</strong> {{ $aventureiro->ouro }}</p>

    <p>
        <a href="{{ route('aventureiros.edit', $aventureiro) }}" class="btn">Editar</a>
        <a href="{{ route('aventureiros.missoes.index', $aventureiro) }}">Vincular missões</a>
        <a href="{{ route('aventureiros.index') }}">Voltar</a>
    </p>

    <h2>Missões concluídas</h2>

    @if ($missoes->isEmpty())
        <div class="notice">Este aventureiro ainda não concluiu nenhuma missão.</div>
    @else
        <div class="table-wrap">
            <table class="roster">
                <thead>
                    <tr>
                        <th>Missão</th>
                        <th>Dificuldade</th>
                        <th class="num">Ouro recebido</th>
                        <th class="num">XP recebido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($missoes as $m)
                        <tr>
                            <td>{{ $m->missao?->missao_nome ?? '—' }}</td>
                            <td>{{ $m->missao?->dificuldade ?? '—' }}</td>
                            <td class="num">{{ $m->ouro_recebido }}</td>
                            <td class="num">{{ $m->xp_recebido }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection