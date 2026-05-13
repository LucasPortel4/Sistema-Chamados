@extends('layouts.app')

@section('title', 'Avaliar Chamado #' . $chamado->id)

@section('content')
<div class="card">
    <h1 style="margin-bottom: 12px;">Avaliar atendimento</h1>
    <p style="color: #666; margin-bottom: 24px;">
        Chamado #{{ $chamado->id }} - {{ $chamado->titulo }}
    </p>

    <form action="{{ route('avaliacao.store', $chamado->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nota">Nota do atendimento *</label>
            <select name="nota" id="nota" required>
                <option value="">Selecione uma nota</option>
                @for($nota = 5; $nota >= 1; $nota--)
                    <option value="{{ $nota }}" {{ (string) old('nota') === (string) $nota ? 'selected' : '' }}>
                        {{ $nota }} - {{ $nota === 5 ? 'Excelente' : ($nota === 4 ? 'Bom' : ($nota === 3 ? 'Regular' : ($nota === 2 ? 'Ruim' : 'Muito ruim'))) }}
                    </option>
                @endfor
            </select>
            @error('nota')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="comentario">Comentario (opcional)</label>
            <textarea name="comentario" id="comentario" placeholder="Conte como foi o atendimento...">{{ old('comentario') }}</textarea>
            @error('comentario')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">Enviar avaliacao</button>
            <a href="{{ route('chamado.status', $chamado->id) }}" class="btn btn-primary">Voltar</a>
        </div>
    </form>
</div>
@endsection
