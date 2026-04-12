@extends('layouts.app')
@section('title','Enviar Documento')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span>
  @if($processo)<a href="{{ route('processos.show',$processo) }}">{{ $processo->numero }}</a><span class="sep">›</span>@endif
  <span class="cur">Enviar Documento</span>
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Enviar Documento</h1><p class="pg-sub">Associe um arquivo a um processo</p></div>
  <a href="{{ $processo ? route('processos.show',$processo) : route('processos.index') }}" class="btn btn-secondary btn-sm">
    <i class="fa-solid fa-arrow-left"></i> Voltar
  </a>
</div>

<form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
@csrf
<div class="grid-main">
  <div style="display:flex;flex-direction:column;gap:16px;">
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fa-solid fa-file-arrow-up" style="color:var(--o5);margin-right:7px;"></i>Arquivo</div></div>
      <div class="card-body">
        <label class="file-drop" for="arquivo">
          <i class="fa-solid fa-cloud-arrow-up"></i>
          <p>Arraste o arquivo aqui ou <strong style="color:var(--b5);">clique para selecionar</strong></p>
          <p style="font-size:11.5px;color:var(--g4);margin-top:4px;">PDF, DOC, DOCX, XLS, XLSX, PNG, JPG · Máx. 20MB</p>
          <input type="file" id="arquivo" name="arquivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg">
        </label>
        @error('arquivo')<span class="invalid-feedback" style="display:block;margin-top:8px;">{{ $message }}</span>@enderror
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div class="card-title">Dados do Documento</div></div>
      <div class="card-body">
        <div class="form-grid" style="gap:16px;">
          <div class="form-group span2">
            <label class="form-label" for="nome">Nome do Documento <span class="req">*</span></label>
            <input type="text" id="nome" name="nome" class="form-control {{ $errors->has('nome')?'is-invalid':'' }}"
              value="{{ old('nome') }}" placeholder="Ex: Edital de Pregão Eletrônico" required>
            @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div class="form-group span2">
            <label class="form-label" for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" class="form-control" placeholder="Descrição adicional...">{{ old('descricao') }}</textarea>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:14px;">
    <div class="card">
      <div class="card-header"><div class="card-title">Processo e Classificação</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="form-group">
          <label class="form-label" for="processo_id">Processo <span class="req">*</span></label>
          <select id="processo_id" name="processo_id" class="form-control {{ $errors->has('processo_id')?'is-invalid':'' }}" required>
            <option value="">Selecione...</option>
            @foreach($processos as $p)
            <option value="{{ $p->id }}" {{ old('processo_id',$processo?->id)==$p->id?'selected':'' }}>
              {{ $p->numero }} — {{ Str::limit($p->titulo,40) }}
            </option>
            @endforeach
          </select>
          @error('processo_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="tipo_documento">Tipo <span class="req">*</span></label>
          <select id="tipo_documento" name="tipo_documento" class="form-control {{ $errors->has('tipo_documento')?'is-invalid':'' }}" required>
            <option value="">Selecione...</option>
            @foreach($tipos as $k=>$l)<option value="{{ $k }}" {{ old('tipo_documento')==$k?'selected':'' }}>{{ $l }}</option>@endforeach
          </select>
          @error('tipo_documento')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
          <label class="form-label" for="versao">Versão</label>
          <input type="text" id="versao" name="versao" class="form-control mono" value="{{ old('versao','1.0') }}" placeholder="Ex: 1.0">
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
        <button type="submit" class="btn btn-orange" style="width:100%;justify-content:center;padding:10px;">
          <i class="fa-solid fa-cloud-arrow-up"></i> Enviar Documento
        </button>
        <a href="{{ route('processos.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;padding:10px;">Cancelar</a>
      </div>
    </div>
  </div>
</div>
</form>
@endsection
