@extends('layouts.app')
@section('title','Meu Perfil')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span><span class="cur">Meu Perfil</span>
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Meu Perfil</h1><p class="pg-sub">Gerencie suas informações de acesso</p></div>
</div>

<div style="max-width:700px;display:flex;flex-direction:column;gap:18px;">

  {{-- Info --}}
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-user" style="color:var(--b4);margin-right:7px;"></i>Informações da Conta</div></div>
    <div class="card-body">
      <div style="display:flex;align-items:center;gap:18px;margin-bottom:20px;padding:16px;background:var(--g0);border-radius:var(--r);border:1px solid var(--g2);">
        <div style="width:56px;height:56px;border-radius:50%;background:var(--b5);display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;flex-shrink:0;">{{ $user->initials }}</div>
        <div>
          <div style="font-size:17px;font-weight:600;color:var(--g9);">{{ $user->name }}</div>
          <div style="font-size:13px;color:var(--g5);margin-top:2px;">{{ $user->email }}</div>
          <div style="margin-top:5px;display:flex;gap:7px;align-items:center;">
            <span class="badge badge-{{ $user->role_color }}">{{ $user->role_label }}</span>
            @if($user->hasVerifiedEmail())
              <span style="font-size:11px;color:var(--ok);"><i class="fa-solid fa-circle-check"></i> E-mail verificado</span>
            @else
              <span style="font-size:11px;color:var(--warn);"><i class="fa-solid fa-clock"></i> E-mail pendente de verificação</span>
            @endif
          </div>
        </div>
      </div>
      <div class="det-grid" style="border:1px solid var(--g2);border-radius:var(--r);overflow:hidden;">
        <div class="det-item"><div class="det-lbl">Cargo</div><div class="det-val">{{ $user->cargo ?? '—' }}</div></div>
        <div class="det-item"><div class="det-lbl">Setor</div><div class="det-val">{{ $user->setor ?? '—' }}</div></div>
        <div class="det-item"><div class="det-lbl">Membro desde</div><div class="det-val">{{ $user->created_at->format('d/m/Y') }}</div></div>
        <div class="det-item"><div class="det-lbl">Último acesso</div><div class="det-val">{{ $user->updated_at->format('d/m/Y H:i') }}</div></div>
      </div>
    </div>
  </div>

  {{-- Alterar senha --}}
  <div class="card" id="senha">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-lock" style="color:var(--warn);margin-right:7px;"></i>Alterar Senha</div></div>
    <div class="card-body">
      @if(session('success') && str_contains(session('success'),'Senha'))
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      <form method="POST" action="{{ route('perfil.senha') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:14px;">
          <div class="form-group">
            <label class="form-label" for="senha_atual">Senha Atual <span class="req">*</span></label>
            <input type="password" id="senha_atual" name="senha_atual"
              class="form-control {{ $errors->has('senha_atual')?'is-invalid':'' }}" required>
            @error('senha_atual')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="password">Nova Senha <span class="req">*</span></label>
            <input type="password" id="password" name="password"
              class="form-control {{ $errors->has('password')?'is-invalid':'' }}" placeholder="Mínimo 8 caracteres" required>
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar Nova Senha <span class="req">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
          </div>
          <div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-lock"></i> Alterar Senha</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Alterar e-mail --}}
  <div class="card" id="email">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-envelope" style="color:var(--b4);margin-right:7px;"></i>Alterar E-mail</div></div>
    <div class="card-body">
      @if(session('success') && str_contains(session('success'),'E-mail'))
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
      @endif
      <div style="background:var(--warn-bg);border-left:3px solid var(--warn);border-radius:var(--r);padding:10px 14px;margin-bottom:18px;">
        <p style="font-size:13px;color:var(--warn);"><i class="fa-solid fa-triangle-exclamation" style="margin-right:6px;"></i>
        Ao alterar o e-mail, você precisará verificar o novo endereço antes de continuar usando o sistema.</p>
      </div>
      <form method="POST" action="{{ route('perfil.email') }}">
        @csrf
        <div style="display:flex;flex-direction:column;gap:14px;">
          <div class="form-group">
            <label class="form-label">E-mail Atual</label>
            <div style="padding:8px 12px;background:var(--g1);border:1.5px solid var(--g2);border-radius:var(--r);font-size:13.5px;color:var(--g6);font-family:var(--mono);">{{ $user->email }}</div>
          </div>
          <div class="form-group">
            <label class="form-label" for="email_novo">Novo E-mail <span class="req">*</span></label>
            <input type="email" id="email_novo" name="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
              placeholder="novo@email.com" required>
            @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="senha_email">Senha Atual (confirmação) <span class="req">*</span></label>
            <input type="password" id="senha_email" name="senha_atual"
              class="form-control {{ $errors->has('senha_atual')?'is-invalid':'' }}" required>
            @error('senha_atual')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-envelope"></i> Alterar E-mail</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
