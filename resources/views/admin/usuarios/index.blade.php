@extends('layouts.app')
@section('title','Usuários')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span><span class="cur">Usuários</span>
@endsection
@section('navbar-actions')
  <a href="{{ route('admin.usuarios.create') }}" class="btn btn-orange btn-sm"><i class="fa-solid fa-user-plus"></i> Novo Usuário</a>
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Gestão de Usuários</h1><p class="pg-sub">{{ $usuarios->total() }} usuário(s) cadastrado(s)</p></div>
</div>

<div class="card">
  <div class="table-wrap">
    <table class="dt">
      <thead><tr><th>Nome</th><th>E-mail</th><th>Cargo/Setor</th><th>Perfil</th><th>E-mail Verificado</th><th>Ativo</th><th style="text-align:center">Ações</th></tr></thead>
      <tbody>
      @forelse($usuarios as $u)
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:9px;">
            <div style="width:32px;height:32px;border-radius:50%;background:var(--b0);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--b5);flex-shrink:0;">{{ $u->initials }}</div>
            <div class="td-bold">{{ $u->name }}@if($u->id===Auth::id()) <span class="badge badge-secondary" style="font-size:9px;margin-left:4px;">você</span>@endif</div>
          </div>
        </td>
        <td class="mono" style="font-size:12.5px;">{{ $u->email }}</td>
        <td class="td-muted">{{ $u->cargo ?? '—' }}<br>{{ $u->setor ?? '' }}</td>
        <td><span class="badge badge-{{ $u->role_color }}">{{ $u->role_label }}</span></td>
        <td>
          @if($u->hasVerifiedEmail())
            <span style="color:var(--ok);font-size:12px;"><i class="fa-solid fa-circle-check"></i> {{ $u->email_verified_at->format('d/m/Y') }}</span>
          @else
            <span style="color:var(--warn);font-size:12px;"><i class="fa-solid fa-clock"></i> Pendente</span>
          @endif
        </td>
        <td>
          @if($u->ativo)
            <span style="color:var(--ok);font-size:12px;"><i class="fa-solid fa-circle-check"></i> Ativo</span>
          @else
            <span style="color:var(--err);font-size:12px;"><i class="fa-solid fa-circle-xmark"></i> Inativo</span>
          @endif
        </td>
        <td>
          <div class="d-flex gap-1" style="justify-content:center;">
            <a href="{{ route('admin.usuarios.edit',$u) }}" class="btn-icon" title="Editar"><i class="fa-solid fa-pen"></i></a>
            @if(!$u->hasVerifiedEmail())
            <form method="POST" action="{{ route('admin.usuarios.reenviar',$u) }}">
              @csrf
              <button type="submit" class="btn-icon" title="Reenviar verificação" style="color:var(--warn);"><i class="fa-solid fa-envelope"></i></button>
            </form>
            @endif
            @if($u->id !== Auth::id() && !$u->isSuperAdmin())
            <form method="POST" action="{{ route('admin.usuarios.destroy',$u) }}" onsubmit="return confirm('Excluir o usuário {{ $u->name }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-icon danger" title="Excluir"><i class="fa-solid fa-trash"></i></button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7"><div class="empty"><i class="fa-solid fa-users"></i><h4>Nenhum usuário</h4></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($usuarios->hasPages())
  <div class="pag-wrap">
    <span>Exibindo {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }}</span>
    {{ $usuarios->links() }}
  </div>
  @endif
</div>
@endsection
