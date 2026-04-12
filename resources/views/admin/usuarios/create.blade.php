@extends('layouts.app')
@section('title','Novo Usuário')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span>
  <a href="{{ route('admin.usuarios.index') }}">Usuários</a><span class="sep">›</span><span class="cur">Novo</span>
@endsection
@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Cadastrar Usuário</h1><p class="pg-sub">Um e-mail de verificação será enviado automaticamente</p></div>
  <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>
<form method="POST" action="{{ route('admin.usuarios.store') }}">
@csrf
<div style="max-width:700px;">
  <div class="card" style="margin-bottom:14px;">
    <div class="card-header"><div class="card-title"><i class="fa-solid fa-user-plus" style="color:var(--o5);margin-right:7px;"></i>Dados do Usuário</div></div>
    <div class="card-body">
      <div class="form-grid" style="gap:16px;">
        <div class="form-group span2">
          <label class="form-label" for="name">Nome completo <span class="req">*</span></label>
          <input type="text" id="name" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}"
            value="{{ old('name') }}" required>
          @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group span2">
          <label class="form-label" for="email">E-mail <span class="req">*</span></label>
          <input type="email" id="email" name="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
            value="{{ old('email') }}" required>
          @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="cargo">Cargo</label>
          <input type="text" id="cargo" name="cargo" class="form-control" value="{{ old('cargo') }}" placeholder="Ex: Pregoeiro">
        </div>
        <div class="form-group">
          <label class="form-label" for="setor">Setor / Secretaria</label>
          <input type="text" id="setor" name="setor" class="form-control" value="{{ old('setor') }}" placeholder="Ex: Sec. de Administração">
        </div>
        <div class="form-group">
          <label class="form-label" for="role">Perfil de Acesso <span class="req">*</span></label>
          <select id="role" name="role" class="form-control {{ $errors->has('role')?'is-invalid':'' }}" required>
            <option value="">Selecione...</option>
            @foreach($roles as $k=>$l)
              @if($k !== 'super_admin' || Auth::user()->isSuperAdmin())
              <option value="{{ $k }}" {{ old('role')==$k?'selected':'' }}>{{ $l }}</option>
              @endif
            @endforeach
          </select>
          @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group" style="justify-content:center;">
          <label class="form-label">Status</label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:4px;">
            <input type="checkbox" name="ativo" value="1" {{ old('ativo',true)?'checked':'' }} style="width:16px;height:16px;accent-color:var(--b5);">
            <span style="font-size:13px;color:var(--g7);">Usuário ativo</span>
          </label>
        </div>
        <div class="form-group">
          <label class="form-label" for="password">Senha <span class="req">*</span></label>
          <input type="password" id="password" name="password" class="form-control {{ $errors->has('password')?'is-invalid':'' }}"
            placeholder="Mínimo 8 caracteres" required>
          @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="password_confirmation">Confirmar senha <span class="req">*</span></label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>
      </div>
    </div>
    <div class="card-footer" style="display:flex;gap:8px;">
      <button type="submit" class="btn btn-orange"><i class="fa-solid fa-user-plus"></i> Cadastrar Usuário</button>
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </div>

  <div style="background:var(--b0);border:1px solid var(--b1);border-radius:var(--r);padding:12px 16px;">
    <p style="font-size:13px;color:var(--b6);"><i class="fa-solid fa-envelope" style="margin-right:6px;"></i>
    Um e-mail de verificação será enviado automaticamente para o endereço informado. O usuário só terá acesso ao sistema após confirmar o e-mail.</p>
  </div>
</div>
</form>
@endsection
