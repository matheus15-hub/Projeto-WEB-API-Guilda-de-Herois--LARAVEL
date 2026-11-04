@extends('layouts.app')

@section('title', 'Editar aventureiro · Guilda de Heróis')

@section('content')
<div class="page">
    <h1>Editar {{ $aventureiro->aventureiro_nome }}</h1>

    <form method="POST" action="{{ route('aventureiros.update', request()->route('aventureiro')) }}" novalidate>
        @csrf
        @method('PUT')

        <div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="{{ old('nome', $aventureiro->aventureiro_nome) }}" required>
            @error('nome') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="idade">Idade</label>
            <input type="number" id="idade" name="idade" value="{{ old('idade', $aventureiro->idade) }}" min="1" required>
            @error('idade') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="id_classe">Classe</label>
            <select id="id_classe" name="id_classe" required>
                <option value="">Selecione…</option>
                @foreach ($classes as $c)
                    <option value="{{ $c->classe_id }}" @selected(old('id_classe', $aventureiro->id_classe) == $c->classe_id)>
                        {{ $c->classe_nome }}
                    </option>
                @endforeach
            </select>
            @error('id_classe') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="experiencia">Experiência</label>
            <input type="number" id="experiencia" name="experiencia" value="{{ old('experiencia', $aventureiro->experiencia) }}" min="0">
            @error('experiencia') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="ouro">Ouro</label>
            <input type="number" id="ouro" name="ouro" value="{{ old('ouro', $aventureiro->ouro) }}" min="0">
            @error('ouro') <p class="field-error">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="btn">Salvar</button>
        <a href="{{ route('aventureiros.index') }}">Cancelar</a>
    </form>
</div>
@endsection