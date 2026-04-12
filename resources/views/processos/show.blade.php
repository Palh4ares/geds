@extends('layouts.app')
@section('title',$processo->numero)
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span>
  <a href="{{ route('processos.index') }}">Processos</a><span class="sep">›</span>
  <span class="cur">{{ $processo->numero }}</span>
@endsection
@section('navbar-actions')
  @if(Auth::user()->canEdit())
  <a href="{{ route('processos.edit',$processo) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-pen"></i> Editar</a>
  @endif
  @if(Auth::user()->canEdit())
  <a href="{{ route('documentos.create',['processo_id'=>$processo->id]) }}" class="btn btn-orange btn-sm">
    <i class="fa-solid fa-file-arrow-up"></i> Enviar Doc.
  </a>
  @endif
@endsection

@section('content')
<div class="pg-hdr">
  <div>
    <div style="display:flex;align-items:center;gap:9px;margin-bottom:3px;">
      <h1 class="pg-title" style="margin-bottom:0;">{{ $processo->titulo }}</h1>
      <span class="badge badge-{{ $processo->status_color }}">{{ $processo->status_label }}</span>
    </div>
    <p class="pg-sub mono">{{ $processo->numero }} · {{ $processo->tipo_label }}
      @if($processo->secretaria) · {{ $processo->secretaria }}@endif
    </p>
  </div>
</div>

<div class="grid-main">
  <div style="display:flex;flex-direction:column;gap:16px;">

    @if($processo->objeto)
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fa-solid fa-bullseye" style="color:var(--o5);margin-right:7px;"></i>Objeto</div></div>
      <div class="card-body"><p style="line-height:1.8;color:var(--g7);">{{ $processo->objeto }}</p></div>
    </div>
    @endif

    <div class="card">
      <div class="card-header"><div class="card-title">Informações do Processo</div></div>
      <div class="det-grid">
        @foreach([
          ['Número','numero','mono'],['Ano','ano',null],['Tipo','tipo_label',null],
          ['Status','status_label',null],['Secretaria','secretaria',null],
          ['Valor Estimado','valor_estimado',null],['Data de Abertura','data_abertura',null],
          ['Data de Encerramento','data_encerramento',null],['Criado por','criador.name',null],
          ['Cadastrado em','created_at',null],
        ] as [$lbl,$campo,$cls])
        <div class="det-item">
          <div class="det-lbl">{{ $lbl }}</div>
          <div class="det-val {{ $cls }}">
            @if($campo === 'status_label')
              <span class="badge badge-{{ $processo->status_color }}">{{ $processo->status_label }}</span>
            @elseif($campo === 'valor_estimado')
              {{ $processo->valor_estimado ? 'R$ '.number_format($processo->valor_estimado,2,',','.') : '—' }}
            @elseif(in_array($campo,['data_abertura','data_encerramento']))
              {{ $processo->$campo?->format('d/m/Y') ?? '—' }}
            @elseif($campo === 'created_at')
              {{ $processo->created_at->format('d/m/Y H:i') }}
            @elseif($campo === 'criador.name')
              {{ $processo->criador->name ?? '—' }}
            @else
              {{ $processo->$campo ?? '—' }}
            @endif
          </div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- DOCUMENTOS --}}
    <div class="card">
      <div class="card-header">
        <div><div class="card-title"><i class="fa-solid fa-file-lines" style="color:var(--b4);margin-right:7px;"></i>Documentos ({{ $processo->documentos->count() }})</div></div>
        @if(Auth::user()->canEdit())
        <a href="{{ route('documentos.create',['processo_id'=>$processo->id]) }}" class="btn btn-orange btn-sm">
          <i class="fa-solid fa-upload"></i> Enviar
        </a>
        @endif
      </div>
      @if($processo->documentos->isEmpty())
      <div class="empty"><i class="fa-solid fa-file-circle-plus"></i><h4>Nenhum documento</h4><p>Envie o primeiro documento para este processo.</p></div>
      @else
      <div class="table-wrap">
        <table class="dt">
          <thead><tr><th>Documento</th><th>Tipo</th><th>Versão</th><th>Tamanho</th><th>Enviado em</th><th style="text-align:center">Ações</th></tr></thead>
          <tbody>
          @foreach($processo->documentos as $doc)
          <tr>
            <td>
              <div class="td-bold">{{ $doc->nome }}</div>
              @if($doc->descricao)<div class="td-muted">{{ Str::limit($doc->descricao,60) }}</div>@endif
              <div class="td-muted mono" style="font-size:10.5px;">{{ $doc->arquivo_nome_original }}</div>
            </td>
            <td><span class="badge badge-secondary" style="font-size:10px;">{{ $doc->tipo_label }}</span></td>
            <td class="mono" style="font-size:11.5px;">v{{ $doc->versao }}</td>
            <td class="td-muted">{{ $doc->tamanho_formatado }}</td>
            <td class="td-muted">{{ $doc->created_at->format('d/m/Y') }}<br><span class="mono" style="font-size:10px;">{{ $doc->enviador->name ?? '—' }}</span></td>
            <td>
              <div class="d-flex gap-1" style="justify-content:center;">
                @if($doc->isPdf())
                <a href="{{ route('documentos.visualizar',$doc) }}" target="_blank" class="btn-icon" title="Visualizar PDF"><i class="fa-solid fa-eye"></i></a>
                @endif
                <a href="{{ route('documentos.download',$doc) }}" class="btn-icon" title="Download"><i class="fa-solid fa-download"></i></a>
                @if(Auth::user()->canEdit())
                <a href="{{ route('documentos.edit',$doc) }}" class="btn-icon" title="Editar"><i class="fa-solid fa-pen"></i></a>
                @endif
                @if(Auth::user()->isAdmin())
                <form method="POST" action="{{ route('documentos.destroy',$doc) }}" onsubmit="return confirm('Excluir este documento?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn-icon danger" title="Excluir"><i class="fa-solid fa-trash"></i></button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>

    {{-- HISTÓRICO --}}
    @if($historico->count() > 0)
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:var(--g5);margin-right:7px;"></i>Histórico deste Processo</div></div>
      <div class="table-wrap">
        <table class="dt">
          <thead><tr><th>Ação</th><th>Usuário</th><th>Descrição</th><th>Data/Hora</th></tr></thead>
          <tbody>
          @foreach($historico as $log)
          <tr>
            <td><span class="badge badge-info" style="font-size:10px;">{{ \App\Models\Auditoria::labelAcao($log->acao) }}</span></td>
            <td class="td-bold">{{ $log->user->name ?? 'Sistema' }}</td>
            <td>{{ $log->descricao }}</td>
            <td class="td-muted mono">{{ $log->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif

  </div>

  {{-- LATERAL --}}
  <div style="display:flex;flex-direction:column;gap:14px;">
    @if($processo->descricao)
    <div class="card">
      <div class="card-header"><div class="card-title">Observações</div></div>
      <div class="card-body"><p style="font-size:13.5px;color:var(--g6);line-height:1.7;">{{ $processo->descricao }}</p></div>
    </div>
    @endif

    @if(Auth::user()->canEdit() || Auth::user()->isAdmin())
    <div class="card">
      <div class="card-header"><div class="card-title">Ações</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
        @if(Auth::user()->canEdit())
        <a href="{{ route('processos.edit',$processo) }}" class="btn btn-secondary" style="width:100%;justify-content:center;padding:10px;">
          <i class="fa-solid fa-pen"></i> Editar Processo
        </a>
        <a href="{{ route('documentos.create',['processo_id'=>$processo->id]) }}" class="btn btn-orange" style="width:100%;justify-content:center;padding:10px;">
          <i class="fa-solid fa-file-arrow-up"></i> Enviar Documento
        </a>
        @endif
        @if(Auth::user()->isAdmin())
        <hr style="border:none;border-top:1px solid var(--g2);margin:2px 0;">
        <form method="POST" action="{{ route('processos.destroy',$processo) }}"
          onsubmit="return confirm('ATENÇÃO: Excluir {{ $processo->numero }} e todos os seus documentos?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center;padding:10px;">
            <i class="fa-solid fa-trash"></i> Excluir Processo
          </button>
        </form>
        @endif
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
