@extends('layouts.app')

@section('title', 'Chamado Criado')

@section('content')
<div class="card" style="text-align: center;">
    <h1 style="color: #27ae60; margin-bottom: 20px;">Chamado criado com sucesso!</h1>

    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <p style="font-size: 18px; margin-bottom: 10px;">
            <strong>Numero do chamado: #{{ $chamado->id }}</strong>
        </p>
        <p style="color: #666;">
            Este chamado foi vinculado a este navegador e aparecera na lista "Meus Chamados".
        </p>
    </div>

    <div style="margin-top: 30px;">
        <p style="margin-bottom: 20px;">Seu chamado foi registrado e em breve entraremos em contato.</p>
        <p><strong>Status atual:</strong> <span class="status-badge status-{{ $chamado->status }}">{{ \App\Models\Chamado::getStatusLabel($chamado->status) }}</span></p>
    </div>

    <div style="margin-top: 30px;">
        <a href="{{ route('chamado.consultar') }}" class="btn btn-primary">Ver Meus Chamados</a>
        <a href="{{ route('chamado.criar') }}" class="btn btn-success" style="margin-left: 10px;">Abrir Outro Chamado</a>
    </div>
</div>
@endsection
