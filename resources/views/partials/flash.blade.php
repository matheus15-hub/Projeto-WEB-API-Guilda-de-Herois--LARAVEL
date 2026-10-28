@if (session('sucesso'))
    <div class="flash" role="status">{{ session('sucesso') }}</div>
@endif
@if (session('erro'))
    <div class="flash erro" role="alert">{{ session('erro') }}</div>
@endif