@extends('layouts.app')
@section('title','Editar Usuário')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span>
  <a href="{{ route('admin.usuarios.index') }}">Usuários</a><span class="sep">›</span><span class="cur">Editar</span>
@endsection
@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Editar Usuário</h1><p class="pg-sub">{{ $usuario->email }}</p></div>
  <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>
<form method="POST" action="{{ route('admin.usuarios.update',$usuario) }}">
@csrf @method('PUT')
<div style="max-width:700px;">
  <div class="card">
    <div class="card-header"><div class="card-title">Dados do Usuário</div></div>
    <div class="card-body">
      <div class="form-grid" style="gap:16px;">
        <div class="form-group span2">
          <label class="form-label" for="name">Nome <span class="req">*</span></label>
          <input type="text" id="name" name="name" class="form-control {{ $errors->has('name')?'is-invalid':'' }}"
            value="{{ old('name',$usuario->name) }}" required>
          @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group span2">
          <label class="form-label" for="email">E-mail <span class="req">*</span></label>
          <input type="email" id="email" name="email" class="form-control {{ $errors->has('email')?'is-invalid':'' }}"
            value="{{ old('email',$usuario->email) }}" required>
          @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="cargo">Cargo</label>
          <input type="text" id="cargo" name="cargo" class="form-control" value="{{ old('cargo',$usuario->cargo) }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="setor">Setor</label>
          <input type="text" id="setor" name="setor" class="form-control" value="{{ old('setor',$usuario->setor) }}">
        </div>
        <div class="form-group">
          <label class="form-label" for="role">Perfil <span class="req">*</span></label>
          <select id="role" name="role" class="form-control" required {{ $usuario->isSuperAdmin() && !Auth::user()->isSuperAdmin() ? 'disabled' : '' }}>
            @foreach($roles as $k=>$l)
              @if($k !== 'super_admin' || Auth::user()->isSuperAdmin())
              <option value="{{ $k }}" {{ old('role',$usuario->role)==$k?'selected':'' }}>{{ $l }}</option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="form-group" style="justify-content:center;">
          <label class="form-label">Status</label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding-top:4px;">
            <input type="checkbox" name="ativo" value="1" {{ old('ativo',$usuario->ativo)?'checked':'' }} style="width:16px;height:16px;accent-color:var(--b5);">
            <span style="font-size:13px;color:var(--g7);">Usuário ativo</span>
          </label>
        </div>
      </div>
    </div>
    <div class="card-footer" style="display:flex;gap:8px;">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
      <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </div>
</div>
</form>
@endsection
