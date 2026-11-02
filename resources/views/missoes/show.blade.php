@extends('layouts.app')

@section('title', $missao->missao_nome . ' · Guilda de Heróis')

@section('content')
<div class="page">
    <div class="toolbar">
        <h1>{{ $missao->missao_nome }}</h1>
        <a href="{{ route('missoes.index') }}">Voltar</a>
    </div>

    @include('partials.flash')

    <p><strong>Dificuldade:</strong> {{ $missao->dificuldade }}</p>
    <p>
        <strong>Monstro:</strong>
        {{ $missao->monstro?->monstro_nome ?? '—' }}
        @if ($missao->quantidade_monstro) × {{ $missao->quantidade_monstro }} @endif
    </p>
    <p><strong>Recompensa:</strong> {{ $missao->recompensa_ouro }} de ouro</p>

    {{-- ===== Quem já concluiu ===== --}}
    <h2>Aventureiros que concluíram</h2>

    @if ($concluidas->isEmpty())
        <div class="notice">Ninguém da sua guilda concluiu esta missão ainda.</div>
    @else
        <div class="table-wrap">
            <table class="roster">
                <thead>
                    <tr>
                        <th>Aventureiro</th>
                        <th class="num">Ouro recebido</th>
                        <th class="num">XP recebido</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($concluidas as $c)
                        <tr>
                            <td>{{ $c->aventureiro_nome }}</td>
                            <td class="num">{{ $c->ouro_recebido }}</td>
                            <td class="num">{{ $c->xp_recebido }}</td>
                            <td class="actions">
                                <form method="POST"
                                      action="{{ route('missoes.desvincular', ['missao' => $missao, 'aventureiro' => $c->aventureiro_id]) }}"
                                      style="display:inline"
                                      onsubmit="return confirm('Remover {{ $c->aventureiro_nome }} desta missão?')">
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

    {{-- ===== Vincular ===== --}}
    <h2>Vincular aventureiro</h2>

    @if ($disponiveis->isEmpty())
        <div class="notice">Todos os aventureiros da guilda já concluíram esta missão.</div>
    @else
        <form method="POST" action="{{ route('missoes.vincular', $missao) }}">
            @csrf

            <div class="field {{ $errors->has('id_aventureiro') ? 'has-error' : '' }}">
                <label for="id_aventureiro">Aventureiro</label>
                <select id="id_aventureiro" name="id_aventureiro" required>
                    <option value="">Selecione…</option>
                    @foreach ($disponiveis as $a)
                        <option value="{{ $a->aventureiro_id }}">{{ $a->aventureiro_nome }}</option>
                    @endforeach
                </select>
                @error('id_aventureiro') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn">Vincular</button>
        </form>
    @endif

    {{-- ===== Trocar ===== --}}
    @if ($concluidas->isNotEmpty() && $disponiveis->isNotEmpty())
        <h2>Passar a missão para outro aventureiro</h2>

        <form method="POST" action="{{ route('missoes.trocar', $missao) }}">
            @csrf

            <div class="field {{ $errors->has('antigo') ? 'has-error' : '' }}">
                <label for="antigo">De (quem concluiu)</label>
                <select id="antigo" name="antigo" required>
                    <option value="">Selecione…</option>
                    @foreach ($concluidas as $c)
                        <option value="{{ $c->aventureiro_id }}">{{ $c->aventureiro_nome }}</option>
                    @endforeach
                </select>
                @error('antigo') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <div class="field {{ $errors->has('novo') ? 'has-error' : '' }}">
                <label for="novo">Para (novo aventureiro)</label>
                <select id="novo" name="novo" required>
                    <option value="">Selecione…</option>
                    @foreach ($disponiveis as $a)
                        <option value="{{ $a->aventureiro_id }}">{{ $a->aventureiro_nome }}</option>
                    @endforeach
                </select>
                @error('novo') <p class="field-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn">Trocar</button>
        </form>
    @endif
</div>
@endsection