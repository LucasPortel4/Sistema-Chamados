@extends('layouts.app')

@section('title', 'Chamado #' . $chamado->id)

@section('content')
<div class="sc-page-header">
    <h1>Chamado #{{ $chamado->id }}</h1>
    <a href="{{ route('admin.chamados.index') }}" class="btn btn-primary">Voltar</a>
</div>

<div class="card">
    <h2 style="margin-bottom: 20px;">Informacoes do Cliente</h2>

    <div class="sc-two-col-grid">
        <div>
            <p><strong>Nome:</strong> {{ $chamado->nome_cliente }}</p>
            <p><strong>Ramal:</strong> {{ $chamado->ramal }}</p>
            @if($chamado->area_usuario)
                <p><strong>Area do Usuario:</strong> {{ $chamado->area_usuario }}</p>
            @endif
        </div>
        <div>
            <p><strong>Criado em:</strong> {{ $chamado->created_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Ultima atualizacao:</strong> {{ $chamado->updated_at->format('d/m/Y H:i:s') }}</p>
            <p><strong>Status atual:</strong>
                <span class="status-badge status-{{ $chamado->status }}">
                    {{ \App\Models\Chamado::getStatusLabel($chamado->status) }}
                </span>
            </p>
            <p><strong>Prioridade:</strong>
                @if($chamado->isPrioritario())
                    <span class="priority-badge">Prioritario</span>
                @else
                    Normal
                @endif
            </p>
        </div>
    </div>

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

    <h2 style="margin-bottom: 20px;">Detalhes do Chamado</h2>

    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px;">Titulo:</h3>
        <p style="font-size: 18px;">{{ $chamado->titulo }}</p>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="margin-bottom: 10px;">Descricao:</h3>
        <p style="white-space: pre-wrap; background: #f8f9fa; padding: 15px; border-radius: 4px;">{{ $chamado->descricao }}</p>
    </div>

    @if($chamado->anexo)
        <div style="margin-bottom: 20px;">
            <h3 style="margin-bottom: 10px;">Anexo:</h3>
            <a href="{{ asset('storage/' . $chamado->anexo) }}" target="_blank" class="btn btn-primary">
                Ver Arquivo Anexado
            </a>
        </div>
    @endif

    <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

    <h2 style="margin-bottom: 20px;">Atualizar Status</h2>

    <form action="{{ route('admin.chamados.status', $chamado->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="status">Novo Status</label>
            <select name="status" id="status" required>
                <option value="aguardando_analise" {{ $chamado->status == 'aguardando_analise' ? 'selected' : '' }}>
                    Aguardando Analise
                </option>
                <option value="em_analise" {{ $chamado->status == 'em_analise' ? 'selected' : '' }}>
                    Em Analise
                </option>
                <option value="a_caminho" {{ $chamado->status == 'a_caminho' ? 'selected' : '' }}>
                    A Caminho do Chamado
                </option>
                <option value="completo" {{ $chamado->status == 'completo' ? 'selected' : '' }}>
                    Chamado Completo
                </option>
            </select>
        </div>

        <div class="form-group">
            <label for="observacao">Observacao (opcional)</label>
            <textarea name="observacao" id="observacao" placeholder="Adicione uma observacao sobre a mudanca de status..."></textarea>
        </div>

        <button type="submit" class="btn btn-success">Atualizar Status</button>
    </form>

    @if($chamado->historico->count() > 0)
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #ddd;">

        <h2 style="margin-bottom: 20px;">Historico de Atualizacoes</h2>

        <div style="border-left: 4px solid #3498db; padding-left: 20px;">
            @foreach($chamado->historico as $hist)
                <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                    <p style="color: #666; font-size: 13px; margin-bottom: 8px;">
                        {{ $hist->created_at->format('d/m/Y H:i:s') }}
                    </p>
                    <p style="margin-bottom: 5px;">
                        <span class="status-badge status-{{ $hist->status_anterior }}">
                            {{ \App\Models\Chamado::getStatusLabel($hist->status_anterior) }}
                        </span>
                        <strong style="margin: 0 10px;">&rarr;</strong>
                        <span class="status-badge status-{{ $hist->status_novo }}">
                            {{ \App\Models\Chamado::getStatusLabel($hist->status_novo) }}
                        </span>
                    </p>
                    @if($hist->observacao)
                        <p style="margin-top: 8px; padding: 10px; background: #f8f9fa; border-radius: 4px; font-style: italic;">
                            "{{ $hist->observacao }}"
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
