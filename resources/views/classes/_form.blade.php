@csrf

<div class="field {{ $errors->has('nome') ? 'has-error' : '' }}">
    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome"
           value="{{ old('nome', $classe->classe_nome ?? '') }}" required>
    @error('nome') <p class="field-error">{{ $message }}</p> @enderror
</div>

<div class="field {{ $errors->has('raridade') ? 'has-error' : '' }}">
    <label for="raridade">Raridade</label>
    <select id="raridade" name="raridade" required>
        <option value="">Selecione…</option>
        @foreach ($raridades as $r)
            <option value="{{ $r->value }}"
                @selected(old('raridade', $classe->raridade ?? '') === $r->value)>
                {{ $r->value }}
            </option>
        @endforeach
    </select>
    @error('raridade') <p class="field-error">{{ $message }}</p> @enderror
</div>

<div class="field {{ $errors->has('descricao') ? 'has-error' : '' }}">
    <label for="descricao">Descrição</label>
    <textarea id="descricao" name="descricao" rows="4" required>{{ old('descricao', $classe->descricao ?? '') }}</textarea>
    @error('descricao') <p class="field-error">{{ $message }}</p> @enderror
</div>