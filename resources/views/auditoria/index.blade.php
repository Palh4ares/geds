@extends('layouts.app')
@section('title','Auditoria')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span><span class="cur">Histórico de Auditoria</span>
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Histórico de Auditoria</h1><p class="pg-sub">{{ $logs->total() }} registro(s)</p></div>
</div>

<form method="GET" action="{{ route('auditoria.index') }}">
<div class="srch">
  <select name="acao" class="form-control" style="width:220px;">
    <option value="">Todas as ações</option>
    @foreach($acoes as $a)<option value="{{ $a }}" {{ request('acao')==$a?'selected':'' }}>{{ \App\Models\Auditoria::labelAcao($a) }}</option>@endforeach
  </select>
  <input type="date" name="data" class="form-control" style="width:170px;" value="{{ request('data') }}">
  <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filtrar</button>
  @if(request()->hasAny(['acao','user','data']))
  <a href="{{ route('auditoria.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Limpar</a>
  @endif
</div>
</form>

<div class="card">
  <div class="table-wrap">
    <table class="dt">
      <thead><tr><th>Data/Hora</th><th>Ação</th><th>Usuário</th><th>Descrição</th><th>IP</th></tr></thead>
      <tbody>
      @forelse($logs as $log)
      <tr>
        <td class="mono" style="font-size:12px;white-space:nowrap;">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
        <td><span class="badge badge-info" style="font-size:10px;">{{ \App\Models\Auditoria::labelAcao($log->acao) }}</span></td>
        <td>
          <div class="td-bold">{{ $log->user->name ?? 'Sistema' }}</div>
          @if($log->user)<div class="td-muted" style="font-size:10.5px;">{{ $log->user->role_label }}</div>@endif
        </td>
        <td style="max-width:380px;">{{ $log->descricao }}</td>
        <td class="mono" style="font-size:11.5px;color:var(--g4);">{{ $log->ip ?? '—' }}</td>
      </tr>
      @empty
      <tr><td colspan="5"><div class="empty"><i class="fa-solid fa-clock-rotate-left"></i><h4>Nenhum registro</h4></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
  @if($logs->hasPages())
  <div class="pag-wrap">
    <span>Exibindo {{ $logs->firstItem() }}–{{ $logs->lastItem() }} de {{ $logs->total() }}</span>
    {{ $logs->links() }}
  </div>
  @endif
</div>
@endsection
