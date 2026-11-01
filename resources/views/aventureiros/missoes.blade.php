@extends('layouts.app')

@section('title', 'Missões de ' . $aventureiro->aventureiro_nome . ' · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="toolbar">
        <h1>Missões de {{ $aventureiro->aventureiro_nome }}</h1>
        <div>
            <a href="{{ route('aventureiros.show', $aventureiro) }}">Ver aventureiro</a>
            <a href="{{ route('aventureiros.index') }}" class="btn">Concluir</a>
        </div>
    </div>

    @include('partials.flash')

    <p>Experiência atual: <strong>{{ $aventureiro->experiencia }}</strong> · Ouro atual: <strong>{{ $aventureiro->ouro }}</strong></p>

    <h2>Missões disponíveis</h2>

    @if ($disponiveis->isEmpty())
        <div class="notice">Não há missões disponíveis para vincular.</div>
    @else
        <form method="POST" action="{{ route('aventureiros.missoes.store', $aventureiro) }}">
            @csrf

            @error('missoes') <p class="field-error">{{ $message }}</p> @enderror

            <div class="table-wrap">
                <table class="roster">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Missão</th>
                            <th>Dificuldade</th>
                            <th>Monstro</th>
                            <th class="num">Recompensa (ouro)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disponiveis as $m)
                            <tr>
                                <td>
                                    <input type="checkbox" name="missoes[]" value="{{ $m->missao_id }}"
                                           id="missao{{ $m->missao_id }}" @checked(in_array($m->missao_id, old('missoes', [])))>
                                </td>
                                <td><label for="missao{{ $m->missao_id }}">{{ $m->missao_nome }}</label></td>
                                <td>{{ $m->dificuldade }}</td>
                                <td>{{ $m->monstro?->monstro_nome ?? '—' }} @if ($m->quantidade_monstro) × {{ $m->quantidade_monstro }} @endif</td>
                                <td class="num">{{ $m->recompensa_ouro }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn">Vincular selecionadas</button>
        </form>
    @endif

    <h2>Missões já vinculadas</h2>

    @if ($concluidas->isEmpty())
        <div class="notice">Este aventureiro ainda não tem missões vinculadas.</div>
    @else
        <div class="table-wrap">
            <table class="roster">
                <thead>
                    <tr>
                        <th>Missão</th>
                        <th>Dificuldade</th>
                        <th class="num">Ouro recebido</th>
                        <th class="num">XP recebido</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($concluidas as $c)
                        <tr>
                            <td>{{ $c->missao_nome }}</td>
                            <td>{{ $c->dificuldade }}</td>
                            <td class="num">{{ $c->ouro_recebido }}</td>
                            <td class="num">{{ $c->xp_recebido }}</td>
                            <td>
                                <form method="POST" action="{{ route('aventureiros.missoes.destroy', [$aventureiro, $c->missao_id]) }}" style="display:inline"
                                      onsubmit="return confirm('Remover o vínculo com {{ addslashes($c->missao_nome) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Remover</button>
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