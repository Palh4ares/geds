@extends('layouts.app')
@section('title','Painel')
@section('breadcrumb')
<span class="cur">
    <i class="fa-solid fa-gauge-high" style="margin-right:5px;font-size:11px;color:var(--g4)"></i>
    Painel de Controle
</span>
@endsection
@section('navbar-actions')
  @if(Auth::user()->canManageProcessos())
  <a href="{{ route('processos.create') }}" class="btn btn-orange btn-sm">
    <i class="fa-solid fa-plus"></i> Novo Processo
  </a>
  @endif
@endsection

@section('content')
<div class="pg-hdr">
  <div>
    <h1 class="pg-title">Painel de Controle</h1>
    <p class="pg-sub">Visão geral do sistema — {{ now()->format('d/m/Y') }}</p>
  </div>
</div>

{{-- STATS --}}
<div class="stats-grid">
  <div class="sc"><div class="sc-ico bl"><i class="fa-solid fa-folder-open"></i></div>
    <div><div class="sc-lbl">Total de Processos</div><div class="sc-val">{{ $stats['total'] }}</div></div></div>
  <div class="sc"><div class="sc-ico or"><i class="fa-solid fa-spinner"></i></div>
    <div><div class="sc-lbl">Em Andamento</div><div class="sc-val">{{ $stats['em_andamento'] }}</div></div></div>
  <div class="sc"><div class="sc-ico wn"><i class="fa-solid fa-magnifying-glass-chart"></i></div>
    <div><div class="sc-lbl">Em Análise</div><div class="sc-val">{{ $stats['em_analise'] }}</div></div></div>
  <div class="sc"><div class="sc-ico gn"><i class="fa-solid fa-file-lines"></i></div>
    <div><div class="sc-lbl">Total de Documentos</div><div class="sc-val">{{ $stats['documentos'] }}</div></div></div>
</div>

<div class="grid2" style="margin-bottom:18px;">
  <div class="sc"><div class="sc-ico gn"><i class="fa-solid fa-circle-check"></i></div>
    <div><div class="sc-lbl">Aprovados</div><div class="sc-val">{{ $stats['aprovado'] }}</div></div></div>
  <div class="sc"><div class="sc-ico rd"><i class="fa-solid fa-ban"></i></div>
    <div><div class="sc-lbl">Cancelados</div><div class="sc-val">{{ $stats['cancelado'] }}</div></div></div>
</div>

<div class="grid2" style="margin-bottom:18px;">
  {{-- Processos recentes --}}
  <div class="card">
    <div class="card-header">
      <div><div class="card-title">Processos Recentes</div><div class="card-subtitle">Últimos cadastrados</div></div>
      <a href="{{ route('processos.index') }}" class="btn btn-secondary btn-sm">Ver todos</a>
    </div>
    @forelse($processos_recentes as $p)
    <div style="padding:11px 18px;border-bottom:1px solid var(--g1);display:flex;align-items:center;gap:11px;">
      <div style="width:34px;height:34px;background:var(--b0);border-radius:var(--r);display:flex;align-items:center;justify-content:center;color:var(--b5);font-size:13px;flex-shrink:0;">
        <i class="fa-solid fa-folder"></i></div>
      <div style="flex:1;overflow:hidden;">
        <div style="font-size:13px;font-weight:500;color:var(--g9);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          <a href="{{ route('processos.show',$p) }}" style="color:inherit;">{{ $p->titulo }}</a></div>
        <div style="font-size:11px;color:var(--g4);font-family:var(--mono);">{{ $p->numero }}</div>
      </div>
      <span class="badge badge-{{ $p->status_color }}">{{ $p->status_label }}</span>
    </div>
    @empty
    <div class="empty" style="padding:24px;"><i class="fa-solid fa-folder-open"></i><p>Nenhum processo cadastrado</p></div>
    @endforelse
  </div>

  {{-- Documentos recentes --}}
  <div class="card">
    <div class="card-header">
      <div><div class="card-title">Documentos Recentes</div><div class="card-subtitle">Últimos enviados</div></div>
      @if(Auth::user()->canEdit())
      <a href="{{ route('documentos.create') }}" class="btn btn-orange btn-sm"><i class="fa-solid fa-upload"></i></a>
      @endif
    </div>
    @forelse($documentos_recentes as $d)
    <div style="padding:11px 18px;border-bottom:1px solid var(--g1);display:flex;align-items:center;gap:11px;">
      <div style="width:34px;height:34px;background:#fff5ec;border-radius:var(--r);display:flex;align-items:center;justify-content:center;color:var(--o5);font-size:13px;flex-shrink:0;">
        <i class="fa-solid fa-file-pdf"></i></div>
      <div style="flex:1;overflow:hidden;">
        <div style="font-size:13px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $d->nome }}</div>
        <div style="font-size:11px;color:var(--g4);">{{ $d->processo->numero ?? '—' }}</div>
      </div>
      <div style="font-size:11px;color:var(--g4);flex-shrink:0;">{{ $d->tamanho_formatado }}</div>
    </div>
    @empty
    <div class="empty" style="padding:24px;"><i class="fa-solid fa-file-lines"></i><p>Nenhum documento enviado</p></div>
    @endforelse
  </div>
</div>

{{-- Histórico recente --}}
@if(Auth::user()->isAdmin())
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--b4);margin-right:7px;"></i>Atividade Recente</div>
    <a href="{{ route('auditoria.index') }}" class="btn btn-secondary btn-sm">Ver histórico completo</a>
  </div>
  <div class="table-wrap">
    <table class="dt">
      <thead><tr><th>Ação</th><th>Usuário</th><th>Descrição</th><th>Data/Hora</th></tr></thead>
      <tbody>
      @forelse($historico_recente as $log)
      <tr>
        <td><span class="badge badge-info" style="font-size:10px;">{{ \App\Models\Auditoria::labelAcao($log->acao) }}</span></td>
        <td class="td-bold">{{ $log->user->name ?? 'Sistema' }}</td>
        <td style="max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $log->descricao }}</td>
        <td class="td-muted mono">{{ $log->created_at->format('d/m/Y H:i') }}</td>
      </tr>
      @empty
      <tr><td colspan="4"><div class="empty" style="padding:20px;"><p>Nenhuma atividade registrada</p></div></td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection
