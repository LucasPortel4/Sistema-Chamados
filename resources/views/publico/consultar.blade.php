@extends('layouts.app')

@section('title', 'Meus Chamados')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 20px;">Meus Chamados</h1>

    @if(session('error'))
        <div style="margin-bottom: 20px; padding: 10px; border-radius: 6px; background: #fdecea; color: #b91c1c;">
            {{ session('error') }}
        </div>
    @endif

    @if($chamados->isEmpty())
        <p style="margin-bottom: 20px;">Nenhum chamado encontrado nesta sessao.</p>
        <a href="{{ route('chamado.criar') }}" class="btn btn-success">Abrir Chamado</a>
    @else
        <p style="margin-bottom: 20px; color: #555;">Os chamados abaixo foram criados neste navegador.</p>

        <div style="display: grid; gap: 12px;">
            @foreach($chamados as $chamado)
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <div>
                            <p><strong>#{{ $chamado->id }}</strong> - {{ $chamado->titulo }}</p>
                            <p style="color: #666; font-size: 14px;">{{ $chamado->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="status-badge status-{{ $chamado->status }}">{{ \App\Models\Chamado::getStatusLabel($chamado->status) }}</span>
                            <a href="{{ route('chamado.status', $chamado->id) }}" class="btn btn-primary">Ver detalhes</a>
                            @if($chamado->status === 'completo' && ! $chamado->avaliacao)
                                <a href="{{ route('avaliacao.create', $chamado->id) }}" class="btn btn-success">Avaliar</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
