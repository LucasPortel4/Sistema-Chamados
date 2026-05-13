@extends('layouts.app')

@section('title', 'Abrir Chamado')

@section('content')
<div class="card">
    <h1 style="margin-bottom: 20px;">Abrir Novo Chamado</h1>
    
    <form action="{{ route('chamado.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label for="nome_cliente">Nome Completo *</label>
            <input type="text" name="nome_cliente" id="nome_cliente" required value="{{ old('nome_cliente') }}">
            @error('nome_cliente')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="ramal">Ramal *</label>
            <input type="text" name="ramal" id="ramal" required value="{{ old('ramal') }}" placeholder="0000">
            @error('ramal')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="area_usuario">Área do Usuário</label>
            <input type="text" name="area_usuario" id="area_usuario" value="{{ old('area_usuario') }}">
            @error('area_usuario')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="titulo">Título do Chamado *</label>
            <input type="text" name="titulo" id="titulo" required value="{{ old('titulo') }}" placeholder="Ex: Problema com internet">
            @error('titulo')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="descricao">Descrição do Problema *</label>
            <textarea name="descricao" id="descricao" required placeholder="Descreva detalhadamente o problema...">{{ old('descricao') }}</textarea>
            @error('descricao')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="anexo">Anexar Arquivo (opcional)</label>
            <input type="file" name="anexo" id="anexo" accept=".jpg,.jpeg,.png,.pdf">
            <small style="color: #666;">Formatos aceitos: JPG, PNG, PDF (max. 5MB)</small>
            @error('anexo')
                <small style="color: red; display: block;">{{ $message }}</small>
            @enderror
        </div>
        <div class="creatorname">
            <label for="name">Feito por Lucas Portela</label>
        </div>
        <button type="submit" class="btn btn-primary">Enviar Chamado</button>
    </form>
</div>
@endsection



