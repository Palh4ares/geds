@extends('layouts.app')
@section('title','Processos')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span><span class="cur">Processos</span>
@endsection
@section('navbar-actions')
  @if(Auth::user()->canManageProcessos())
  <a href="{{ route('processos.create') }}" class="btn btn-orange btn-sm"><i class="fa-solid fa-plus"></i> Novo Processo</a>
  @endif
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Processos Licitatórios</h1><p class="pg-sub">{{ $processos->total() }} processo(s) encontrado(s)</p></div>
</div>

<form method="GET" action="{{ route('processos.index') }}">
<div class="srch">
  <div class="si" style="flex:1;max-width:340px;">
    <i class="fa-solid fa-magnifying-glass"></i>
    <input type="text" name="busca" class="form-control" placeholder="Número, título, objeto..." value="{{ request('busca') }}">
  </div>
  <select name="ano" class="form-control" style="width:110px;">
    <option value="">Ano</option>
    @foreach($anos as $a)<option value="{{ $a }}" {{ request('ano')==$a?'selected':'' }}>{{ $a }}</option>@endforeach
  </select>
  <select name="secretaria" class="form-control" style="width:190px;">
    <option value="">Todas as Secretarias</option>
    @foreach($secretarias as $s)<option value="{{ $s }}" {{ request('secretaria')==$s?'selected':'' }}>{{ $s }}</option>@endforeach
  </select>
  <select name="tipo" class="form-control" style="width:190px;">
    <option value="">Todos os tipos</option>
    @foreach($tipos as $k=>$l)<option value="{{ $k }}" {{ request('tipo')==$k?'selected':'' }}>{{ $l }}</option>@endforeach
  </select>
  <select name="status" class="form-control" style="width:160px;">
    <option value="">Todos os status</option>
    @foreach($statusList as $k=>$l)<option value="{{ $k }}" {{ request('status')==$k?'selected':'' }}>{{ $l }}</option>@endforeach
  </select>
  <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filtrar</button>
  @if(request()->hasAny(['busca','tipo','status','secretaria','ano']))
  <a href="{{ route('processos.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Limpar</a>
  @endif
</div>
</form>

<div class="card">
  <div class="table-wrap">
    <table class="dt">
      <thead><tr>
        <th>Número</th><th>Título</th><th>Secretaria</th><th>Tipo</th><th>Status</th><th>Docs</th><th>Criado em</th>
        <th style="width:90px;text-align:center;">Ações</th>
      </tr></thead>
      <tbody>
      @forelse($processos as $p)
      <tr>
        <td class="td-mono">{{ $p->numero }}</td>
        <td><div class="td-bold">{{ $p->titulo }}</div></td>
        <td class="td-muted">{{ $p->secretaria ?? '—' }}</td>
        <td><span class="badge badge-info" style="font-size:10px;">{{ $p->tipo_label }}</span></td>
        <td><span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span></td>
        <td style="text-align:center;font-family:var(--mono);">{{ $p->total_documentos }}</td>
        <td class="td-muted">{{ $p->created_at->format('d/m/Y') }}</td>
        <td>
          <div class="d-flex gap-1" style="justify-content:center;">
            <a href="{{ route('processos.show',$p) }}" class="btn-icon" title="Ver"><i class="fa-solid fa-eye"></i></a>
            @if(Auth::user()->canEdit())
            <a href="{{ route('processos.edit',$p) }}" class="btn-icon" title="Editar"><i class="fa-solid fa-pen"></i></a>
            @endif
            @if(Auth::user()->isAdmin())
            <form method="POST" action="{{ route('processos.destroy',$p) }}" onsubmit="return confirm('Excluir o processo {{ $p->numero }}?')">
              @csrf @method('DELETE')
              <button type="submit" class="btn-icon danger" title="Excluir"><i class="fa-solid fa-trash"></i></button>
            </form>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="8"><div class="empty"><i class="fa-solid fa-folder-open"></i><h4>Nenhum processo encontrado</h4>
        @if(Auth::user()->canManageProcessos())<p><a href="{{ route('processos.create') }}" style="color:var(--b4);">Cadastre o primeiro processo</a></p>@endif
      </div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($processos->hasPages())
  <div class="pag-wrap">
    <span>Exibindo {{ $processos->firstItem() }}–{{ $processos->lastItem() }} de {{ $processos->total() }}</span>
    {{ $processos->links() }}
  </div>
  @endif
</div>
@endsection
