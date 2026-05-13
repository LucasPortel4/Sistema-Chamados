@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<div class="card" style="max-width: 500px; margin: 0 auto;">
    <h1 style="margin-bottom: 20px;">Login Admin</h1>

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" name="username" id="username" required value="{{ old('username') }}">
            @error('username')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <small style="color: red;">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</div>
@endsection
