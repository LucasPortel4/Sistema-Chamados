@extends('layouts.app')

@section('title', 'Todos os Chamados')

@section('content')
<div class="sc-page-header">
    <h1>Todos os Chamados</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Voltar ao Dashboard</a>
</div>

<div class="card">
    @if($chamados->count() > 0)
        <div class="sc-table-wrap">
            <table class="sc-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Ramal</th>
                        <th>Titulo</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Acao</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chamados as $chamado)
                        @php($isPrioritario = $chamado->isPrioritario())
                        <tr style="{{ $isPrioritario ? 'background: #fffaf2;' : '' }}">
                            <td data-label="ID">#{{ $chamado->id }}</td>
                            <td data-label="Cliente">{{ $chamado->nome_cliente }}</td>
                            <td data-label="Ramal">{{ $chamado->ramal }}</td>
                            <td data-label="Titulo">
                                {{ \Illuminate\Support\Str::limit($chamado->titulo, 40) }}
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
                                <a href="{{ route('admin.chamados.show', $chamado->id) }}" class="btn btn-primary" style="padding: 5px 15px; font-size: 14px;">Ver Detalhes</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $chamados->onEachSide(1)->links('components.pagination') }}
        </div>
    @else
        <p style="text-align: center; color: #999; padding: 40px;">Nenhum chamado encontrado.</p>
    @endif
</div>
@endsection
