@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<h1 style="margin-bottom: 30px;">Dashboard Administrativo</h1>

<div class="sc-stats-grid">
    <div class="card" style="text-align: center; background: #3498db; color: white;">
        <h2 style="font-size: 36px; margin-bottom: 10px;">{{ $stats['total'] }}</h2>
        <p>Total de Chamados</p>
    </div>

    <div class="card" style="text-align: center; background: #f39c12; color: white;">
        <h2 style="font-size: 36px; margin-bottom: 10px;">{{ $stats['aguardando'] }}</h2>
        <p>Aguardando Analise</p>
    </div>

    <div class="card" style="text-align: center; background: #9b59b6; color: white;">
        <h2 style="font-size: 36px; margin-bottom: 10px;">{{ $stats['em_analise'] }}</h2>
        <p>Em Analise</p>
    </div>

    <div class="card" style="text-align: center; background: #e74c3c; color: white;">
        <h2 style="font-size: 36px; margin-bottom: 10px;">{{ $stats['a_caminho'] }}</h2>
        <p>A Caminho</p>
    </div>

    <div class="card" style="text-align: center; background: #27ae60; color: white;">
        <h2 style="font-size: 36px; margin-bottom: 10px;">{{ $stats['completos'] }}</h2>
        <p>Completos</p>
    </div>
</div>

<div class="card">
    <h2 style="margin-bottom: 20px;">Chamados Recentes</h2>

    <div class="sc-filter-actions">
        <a href="{{ route('admin.dashboard', ['filtro' => 'todos']) }}"
           class="btn"
           style="padding: 8px 14px; font-size: 14px; {{ $filtroAtual === 'todos' ? 'background: #3498db; color: #fff;' : 'background: #ecf0f1; color: #2c3e50;' }}">
            Todos
        </a>
        <a href="{{ route('admin.dashboard', ['filtro' => 'prioritarios']) }}"
           class="btn"
           style="padding: 8px 14px; font-size: 14px; {{ $filtroAtual === 'prioritarios' ? 'background: #e67e22; color: #fff;' : 'background: #ecf0f1; color: #2c3e50;' }}">
            Prioritarios
        </a>
    </div>

    <p style="margin-top: -8px; margin-bottom: 16px; color: #666; font-size: 13px;">
        Palavras-chave de prioridade: internet, rede, impressora, servidor, sistema, email, entre outras.
    </p>

    @if($chamadosRecentes->count() > 0)
        <div class="sc-table-wrap">
            <table class="sc-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Titulo</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Acao</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chamadosRecentes as $chamado)
                        @php($isPrioritario = $chamado->isPrioritario())
                        <tr style="{{ $isPrioritario ? 'background: #fffaf2;' : '' }}">
                            <td data-label="ID">#{{ $chamado->id }}</td>
                            <td data-label="Cliente">{{ $chamado->nome_cliente }}</td>
                            <td data-label="Titulo">
                                {{ \Illuminate\Support\Str::limit($chamado->titulo, 30) }}
                                @if($isPrioritario)
                                    <span class="priority-badge" style="margin-left: 8px;">Prioritario</span>
                                @endif
                            </td>
                            <td data-label="Status">
                                <span class="status-badge status-{{ $chamado->status }}">
                                    {{ \App\Models\Chamado::getStatusLabel($chamado->status) }}
                                </span>
                            </td>
                            <td data-label="Data">{{ $chamado->created_at->format('d/m/Y H:i') }}</td>
                            <td data-label="Acao">
                                <a href="{{ route('admin.chamados.show', $chamado->id) }}" class="btn btn-primary" style="padding: 5px 15px; font-size: 14px;">Ver</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="text-align: center; color: #999;">
            {{ $filtroAtual === 'prioritarios' ? 'Nenhum chamado prioritario encontrado.' : 'Nenhum chamado encontrado.' }}
        </p>
    @endif

    <div style="margin-top: 20px; text-align: center;">
        <a href="{{ route('admin.chamados.index') }}" class="btn btn-primary">Ver Todos os Chamados</a>
    </div>
</div>
@endsection
