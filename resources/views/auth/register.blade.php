@extends('layouts.auth')
@section('title', 'Cadastro')

@section('content')
    <h2 class="auth-form-title">Criar conta</h2>
    <p class="auth-form-sub">Preencha os dados para solicitar acesso ao sistema</p>

    @if($errors->any())
        <div class="alert-danger">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="name">Nome completo <span style="color:#b91c1c">*</span></label>
            <input type="text" id="name" name="name"
                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                value="{{ old('name') }}" placeholder="Ex: João da Silva" required autofocus>
            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="email">E-mail <span style="color:#b91c1c">*</span></label>
            <input type="email" id="email" name="email"
                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                value="{{ old('email') }}" placeholder="seunome@municipio.gov.br" required>
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label" for="cargo">Cargo</label>
                <input type="text" id="cargo" name="cargo"
                    class="form-control {{ $errors->has('cargo') ? 'is-invalid' : '' }}"
                    value="{{ old('cargo') }}" placeholder="Ex: Pregoeiro">
            </div>
            <div class="form-group">
                <label class="form-label" for="setor">Setor</label>
                <input type="text" id="setor" name="setor"
                    class="form-control {{ $errors->has('setor') ? 'is-invalid' : '' }}"
                    value="{{ old('setor') }}" placeholder="Ex: Licitações">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Senha <span style="color:#b91c1c">*</span></label>
            <input type="password" id="password" name="password"
                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                placeholder="Mínimo 8 caracteres" required>
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar senha <span style="color:#b91c1c">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control" placeholder="Repita a senha" required>
        </div>

        <button type="submit" class="btn-primary">
            <i class="fa-solid fa-user-plus"></i>&ensp;Criar conta
        </button>
    </form>

    <div class="auth-link">
        Já tem conta? <a href="{{ route('login') }}">Fazer login</a>
    </div>
@endsection
