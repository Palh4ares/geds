@extends('layouts.auth')
@section('title','Sessão expirada')
@section('content')
  <div style="text-align:center;margin-bottom:24px;">
    <div style="width:64px;height:64px;background:#fef7e0;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;">
      <i class="fa-solid fa-clock" style="font-size:28px;color:#8a5d00;"></i>
    </div>
    <h2 class="auth-form-title" style="margin-bottom:6px;">Sessão expirada</h2>
    <p class="auth-form-sub">Sua sessão expirou por inatividade ou o formulário foi aberto há muito tempo.</p>
  </div>
  <a href="{{ url()->previous() }}" class="btn-primary" style="display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;">
    <i class="fa-solid fa-rotate-left"></i> Voltar e tentar novamente
  </a>
  <div style="text-align:center;margin-top:16px;">
    <a href="{{ route('login') }}" style="font-size:13px;color:var(--gray-400);text-decoration:none;">← Ir para o login</a>
  </div>
@endsection
