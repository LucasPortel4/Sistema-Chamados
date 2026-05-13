@extends('layouts.app')

@section('title', 'Avaliacoes')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 20px;">Avaliacoes</h1>
    <p style="color: #666; margin-bottom: 20px;">
        Esta area esta pronta para receber o formulario de avaliacao dos chamados.
    </p>

    <a href="{{ route('home') }}" class="btn btn-primary">Voltar para abertura de chamado</a>
</div>
@endsection
