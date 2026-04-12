@extends('layouts.auth')
@section('title','Entrar')
@section('content')
  <h2 class="auth-form-title">Acessar o sistema</h2>
  <p class="auth-form-sub">Entre com suas credenciais institucionais</p>

  @if(session('success'))
    <div style="background:#e8f5ee;color:#1d7a4a;border-left:3px solid #1d7a4a;padding:10px 14px;border-radius:6px;font-size:13px;margin-bottom:18px;display:flex;align-items:flex-start;gap:8px;">
      <i class="fa-solid fa-circle-check" style="margin-top:1px;flex-shrink:0;"></i><span>{{ session('success') }}</span>
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;border-left:3px solid #b91c1c;padding:10px 14px;border-radius:6px;font-size:13px;margin-bottom:18px;">
      <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
    </div>
  @endif

  <form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div class="form-group" style="margin-bottom:14px;">
      <label class="form-label" for="email">E-mail</label>
      <input type="email" id="email" name="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
        value="{{ old('email') }}" placeholder="seunome@municipio.gov.br" required autofocus>
    </div>
    <div class="form-group" style="margin-bottom:14px;">
      <label class="form-label" for="password">Senha</label>
      <input type="password" id="password" name="password"
        class="form-control {{ $errors->has('password')?'is-invalid':'' }}" placeholder="••••••••" required>
    </div>
    <div class="checkbox-row" style="margin-bottom:18px;">
      <input type="checkbox" id="remember" name="remember">
      <label for="remember">Manter conectado</label>
    </div>
    <button type="submit" class="btn-primary">
      <i class="fa-solid fa-arrow-right-to-bracket"></i>&ensp;Entrar no sistema
    </button>
  </form>

  <div style="text-align:center;margin-top:22px;padding-top:18px;border-top:1px solid var(--gray-200);">
    <p style="font-size:12px;color:var(--gray-400);">Não possui acesso? Solicite ao administrador do sistema.</p>
  </div>
@endsection
