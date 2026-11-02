@extends('layouts.app')

@section('title', 'Aventureiros · Guilda de Heróis')


@section('content')
<div class="page">
    <div class="section-head">
        <h1>Aventureiros</h1>
        <span class="count">{{ $aventureiros->total() }} {{ $aventureiros->total() === 1 ? 'registrado' : 'registrados' }}</span>
    </div>

    @include('partials.flash')

    @if ($aventureiros->isEmpty())
        <div class="notice">Nenhum aventureiro cadastrado nesta guilda ainda.</div>
    @else
        <div class="table-wrap">
            <table class="roster">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Classe</th>
                        <th class="num">Idade</th>
                        <th class="num">Experiência</th>
                        <th class="num">Ouro</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($aventureiros as $a)
                        <tr>
                            <td>{{ $a->aventureiro_nome }}</td>
                            <td>{{ $a->classe?->classe_nome ?? '—' }}</td>
                            <td class="num">{{ $a->idade ?? '—' }}</td>
                            <td class="num">{{ $a->experiencia ?? 0 }}</td>
                            <td class="num">{{ $a->ouro ?? 0 }}</td>
                            <td><a href="{{ route('aventureiros.missoes.index', $a) }}">Missões</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
                <form method="GET" action="{{ route('aventureiros.index') }}">
                    <input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome">
                    <button type="submit" class="btn">Buscar</button>
                @if (request('busca'))
                    <a href="{{ route('aventureiros.index') }}">Limpar</a>
                @endif
    </form>
        </div>
    @endif

    <h2>Novo aventureiro</h2>

    <form method="POST" action="{{ route('aventureiros.store') }}">
        @csrf

        <div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required>
            @error('nome') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="idade">Idade</label>
            <input type="number" id="idade" name="idade" value="{{ old('idade') }}" min="1" required>
            @error('idade') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="id_classe">Classe</label>
            <select id="id_classe" name="id_classe" required>
                <option value="">Selecione…</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->classe_id }}" @selected(old('id_classe') == $c->classe_id)>
                        {{ $c->classe_nome }}
                    </option>
                @endforeach
            </select>
            @error('id_classe') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="experiencia">Experiência</label>
            <input type="number" id="experiencia" name="experiencia" value="{{ old('experiencia', 0) }}" min="0">
            @error('experiencia') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="ouro">Ouro</label>
            <input type="number" id="ouro" name="ouro" value="{{ old('ouro', 0) }}" min="0">
            @error('ouro') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn">Cadastrar</button>
    </form>
</div>
@endsection