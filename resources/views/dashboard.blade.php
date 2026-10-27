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

        <div class="section-head">
            <h2>Aventureiros da guilda</h2>
            <span class="count">{{ $aventureiros->count() }} {{ $aventureiros->count() === 1 ? 'registrado' : 'registrados' }}</span>
        </div>

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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        <div class="notice warn">
            Seu usuário não está vinculado a nenhuma guilda. Peça a um administrador para associar sua conta a uma guilda.
        </div>
    @endif
</div>
@endsection