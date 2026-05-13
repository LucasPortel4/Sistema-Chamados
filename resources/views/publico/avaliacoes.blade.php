@extends('layouts.app')

@section('title', 'Avaliacoes')

@section('content')
<div class="sc-page-header">
    <div>
        <h1>Avaliacoes</h1>
        <p style="color: #666; margin-top: 6px;">Feedbacks enviados pelos usuarios apos a conclusao dos chamados.</p>
    </div>
    <a href="{{ route('home') }}" class="btn btn-primary">Abrir chamado</a>
</div>

<div class="sc-stats-grid">
    <div class="card" style="text-align: center;">
        <h2 style="font-size: 34px; margin-bottom: 8px;">{{ $total }}</h2>
        <p>Total de avaliacoes</p>
    </div>
    <div class="card" style="text-align: center;">
        <h2 style="font-size: 34px; margin-bottom: 8px;">{{ $total > 0 ? number_format($media, 1, ',', '.') : '0,0' }}</h2>
        <p>Media geral</p>
    </div>
</div>

<div class="card">
    @if($avaliacoes->count() > 0)
        <div class="evaluation-list">
            @foreach($avaliacoes as $avaliacao)
                <article class="evaluation-card">
                    <div class="evaluation-card-header">
                        <div>
                            <strong>Chamado #{{ $avaliacao->chamado_id }}</strong>
                            @if($avaliacao->chamado)
                                <p>{{ $avaliacao->chamado->titulo }}</p>
                            @endif
                        </div>
                        <span class="evaluation-score">{{ $avaliacao->nota }}/5</span>
                    </div>

                    @if($avaliacao->comentario)
                        <p class="evaluation-comment">"{{ $avaliacao->comentario }}"</p>
                    @else
                        <p class="evaluation-comment evaluation-comment-muted">Sem comentario.</p>
                    @endif

                    <p class="evaluation-meta">
                        Enviada em {{ $avaliacao->created_at->format('d/m/Y H:i') }}
                        @if($avaliacao->chamado)
                            por {{ $avaliacao->chamado->nome_cliente }} - Ramal {{ $avaliacao->chamado->ramal }}
                        @endif
                    </p>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $avaliacoes->onEachSide(1)->links('components.pagination') }}
        </div>
    @else
        <p style="text-align: center; color: #999; padding: 40px;">Nenhuma avaliacao enviada ainda.</p>
    @endif
</div>
@endsection
