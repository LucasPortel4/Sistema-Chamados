@extends('layouts.app')

@section('title', 'Status do Chamado #' . $chamado->id)

@section('content')
<div class="card">
    <h1 style="margin-bottom: 20px;">Chamado #{{ $chamado->id }}</h1>

    <div class="sc-two-col-grid">
        <div>
            <p><strong>Cliente:</strong> {{ $chamado->nome_cliente }}</p>
            <p><strong>Ramal:</strong> {{ $chamado->ramal }}</p>
            @if($chamado->area_usuario)
                <p><strong>Area do Usuario:</strong> {{ $chamado->area_usuario }}</p>
            @endif
        </div>
        <div>
            <p><strong>Criado em:</strong> {{ $chamado->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Atualizado em:</strong> {{ $chamado->updated_at->format('d/m/Y H:i') }}</p>
            <p><strong>Status:</strong> <span class="status-badge status-{{ $chamado->status }}">{{ \App\Models\Chamado::getStatusLabel($chamado->status) }}</span></p>
        </div>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 10px;">Titulo:</h3>
        <p>{{ $chamado->titulo }}</p>
    </div>

    <div style="margin-bottom: 30px;">
        <h3 style="margin-bottom: 10px;">Descricao:</h3>
        <p style="white-space: pre-wrap;">{{ $chamado->descricao }}</p>
    </div>

    @if($chamado->anexo)
        <div style="margin-bottom: 30px;">
            <h3 style="margin-bottom: 10px;">Anexo:</h3>
            <a href="{{ asset('storage/' . $chamado->anexo) }}" target="_blank" class="btn btn-primary">
                Ver Arquivo Anexado
            </a>
        </div>
    @endif

    @if($chamado->status === 'completo')
        <div class="evaluation-callout">
            @if($chamado->avaliacao)
                <h3 style="margin-bottom: 8px;">Obrigado pela avaliacao!</h3>
                <p style="margin-bottom: 10px;">Sua nota para este atendimento foi {{ $chamado->avaliacao->nota }} de 5.</p>
                @if($chamado->avaliacao->comentario)
                    <p style="color: #666;">"{{ $chamado->avaliacao->comentario }}"</p>
                @endif
            @else
                <h3 style="margin-bottom: 8px;">Chamado concluido</h3>
                <p style="margin-bottom: 15px; color: #555;">Conte como foi o atendimento para ajudar a melhorar o servico.</p>
                <a href="{{ route('avaliacao.create', $chamado->id) }}" class="btn btn-success">Avaliar atendimento</a>
            @endif
        </div>
    @endif

    @if($chamado->historico->count() > 0)
        <div>
            <h3 style="margin-bottom: 15px;">Historico de Atualizacoes:</h3>
            <div style="border-left: 3px solid #3498db; padding-left: 20px;">
                @foreach($chamado->historico as $hist)
                    <div style="margin-bottom: 20px;">
                        <p style="color: #666; font-size: 14px;">{{ $hist->created_at->format('d/m/Y H:i') }}</p>
                        <p>
                            <span class="status-badge status-{{ $hist->status_anterior }}">{{ \App\Models\Chamado::getStatusLabel($hist->status_anterior) }}</span>
                            <span class="status-badge status-{{ $hist->status_novo }}">{{ \App\Models\Chamado::getStatusLabel($hist->status_novo) }}</span>
                        </p>
                        @if($hist->observacao)
                            <p style="margin-top: 5px; font-style: italic;">{{ $hist->observacao }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div style="margin-top: 30px;">
        <a href="{{ route('chamado.consultar') }}" class="btn btn-primary">Voltar para Meus Chamados</a>
    </div>
</div>
@endsection
