@extends('layouts.app')

@section('title', 'Muitas tentativas')

@section('content')
@php
    // O middleware throttle envia o header Retry-After (em segundos)
    $espera = (int) ($exception->getHeaders()['Retry-After'] ?? 60);
@endphp

<div class="page">
    <section class="error-card" role="alert">
        <p class="error-code">429</p>
        <h1>Os portões estão fechados</h1>
        <p class="error-text">
            A guilda está a proteger os seus portões. Foram feitas tentativas demais
            em pouco tempo, por isso a entrada foi bloqueada temporariamente.
        </p>

        <p class="error-wait" aria-live="polite">
            Tente novamente em <strong id="contador">{{ gmdate('i:s', $espera) }}</strong>
        </p>

        <div class="error-actions">
            <a id="btn-voltar" href="{{ route('login') }}"
               class="btn {{ $espera > 0 ? 'disabled' : '' }}"
               @if($espera > 0) aria-disabled="true" @endif>
                Voltar ao login
            </a>
            <a href="{{ url('/') }}" class="btn btn-ghost">Página inicial</a>
        </div>
    </section>
</div>

<script>
(() => {
    let s = {{ $espera }};
    const el  = document.getElementById('contador');
    const btn = document.getElementById('btn-voltar');
    const fmt = n => String(Math.floor(n / 60)).padStart(2, '0') + ':' + String(n % 60).padStart(2, '0');

    const liberar = () => {
        el.textContent = '00:00';
        btn.classList.remove('disabled');
        btn.removeAttribute('aria-disabled');
    };

    if (s <= 0) return liberar();

    const t = setInterval(() => {
        s--;
        if (s <= 0) { clearInterval(t); liberar(); }
        else el.textContent = fmt(s);
    }, 1000);
})();
</script>
@endsection