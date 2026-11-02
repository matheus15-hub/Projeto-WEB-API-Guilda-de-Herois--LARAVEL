@extends('layouts.app')

@section('title', 'Missões · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="section-head">
        <h1>Missões</h1>
        <span class="count">{{ $missoes->count() }} {{ $missoes->count() === 1 ? 'missão' : 'missões' }}</span>
    </div>

    @include('partials.flash')
        @if ($missoes->isEmpty())
            <div class="notice">Nenhuma missão cadastrada.</div>
        @else
            <div class="table-wrap">
                <table class="roster">
                    <thead>
                        <tr>
                            <th>Missão</th>
                            <th>Dificuldade</th>
                            <th>Monstro</th>
                            <th class="num">Recompensa (ouro)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($missoes as $m)
                            <tr>
                                <td>{{ $m->missao_nome }}</td>
                                <td>{{ $m->dificuldade }}</td>
                                <td>
                                    {{ $m->monstro?->monstro_nome ?? '—' }}
                                    @if ($m->quantidade_monstro) × {{ $m->quantidade_monstro }} @endif
                                </td>
                                <td class="num">{{ $m->recompensa_ouro }}</td>
                                <td class="actions">
                                    <a href="{{ route('missoes.show', $m) }}">Ver aventureiros</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        </div>
@endsection