@extends('layouts.app')

@section('title', 'Painel · Guilda de Heróis')

@section('content')
<div class="page">
    @if ($guilda)
        <section class="guild-banner">
            <p class="hello">Bem-vindo(a), {{ $user->name }}.</p>
            <h1>{{ $guilda->guilda_nome }}</h1>
            @if ($guilda->lema)
                <p class="motto">“{{ $guilda->lema }}”</p>
            @endif
        </section>

        @include('partials.flash')

        <div class="section-head">
            <h2>Aventureiros da guilda</h2>
            <span class="count">{{ $aventureiros->total() }} {{ $aventureiros->total() === 1 ? 'registrado' : 'registrados' }}</span>
        </div>

        <form method="GET" action="{{ route('dashboard') }}" class="search">
            <input type="text" name="busca" value="{{ request('busca') }}"
                   placeholder="Buscar aventureiro por nome…" aria-label="Buscar aventureiro">
            <button type="submit" class="btn">Buscar</button>
            @if (request('busca'))
                <a href="{{ route('dashboard') }}" class="btn btn-ghost">Limpar</a>
            @endif
            <a href="{{ route('aventureiros.index') }}#novo" class="btn btn-outline">+ Novo aventureiro</a>
        </form>

        @if ($aventureiros->isEmpty())
            <div class="notice">
                {{ request('busca') ? 'Nenhum aventureiro encontrado para essa busca.' : 'Nenhum aventureiro cadastrado nesta guilda ainda.' }}
            </div>
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
                                <td><a href="{{ route('aventureiros.show', $a) }}" class="row-link">{{ $a->aventureiro_nome }}</a></td>
                                <td>{{ $a->classe?->classe_nome ?? '—' }}</td>
                                <td class="num">{{ $a->idade ?? '—' }}</td>
                                <td class="num">{{ $a->experiencia ?? 0 }}</td>
                                <td class="num">{{ $a->ouro ?? 0 }}</td>
                                <td class="actions">
                                    <a href="{{ route('aventureiros.show', $a) }}">Ver</a>
                                    <a href="{{ route('aventureiros.missoes.index', $a) }}">Missões</a>
                                    <a href="{{ route('aventureiros.edit', $a) }}">Editar</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($aventureiros->hasPages())
                <nav class="pager" aria-label="Paginação">
                    @if ($aventureiros->onFirstPage())
                        <span class="btn btn-ghost disabled">‹ Anterior</span>
                    @else
                        <a href="{{ $aventureiros->previousPageUrl() }}" class="btn btn-ghost">‹ Anterior</a>
                    @endif

                    <span class="pager-info">Página {{ $aventureiros->currentPage() }} de {{ $aventureiros->lastPage() }}</span>

                    @if ($aventureiros->hasMorePages())
                        <a href="{{ $aventureiros->nextPageUrl() }}" class="btn btn-ghost">Próxima ›</a>
                    @else
                        <span class="btn btn-ghost disabled">Próxima ›</span>
                    @endif
                </nav>
            @endif
        @endif
    @else
        <div class="notice warn">
            Seu usuário não está vinculado a nenhuma guilda. Peça a um administrador para associar sua conta a uma guilda.
        </div>
    @endif
</div>
@endsection