@extends('layouts.app')
@section('title','Editar Processo')
@section('breadcrumb')
  <a href="{{ route('dashboard') }}">Painel</a><span class="sep">›</span>
  <a href="{{ route('processos.index') }}">Processos</a><span class="sep">›</span>
  <a href="{{ route('processos.show',$processo) }}">{{ $processo->numero }}</a><span class="sep">›</span>
  <span class="cur">Editar</span>
@endsection

@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Editar Processo</h1><p class="pg-sub mono">{{ $processo->numero }}</p></div>
  <a href="{{ route('processos.show',$processo) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>

<form method="POST" action="{{ route('processos.update',$processo) }}">
@csrf @method('PUT')
<div class="grid-main">
  <div style="display:flex;flex-direction:column;gap:16px;">
    <div class="card">
      <div class="card-header"><div class="card-title">Dados do Processo</div></div>
      <div class="card-body">
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Número do Processo</label>
          <div style="display:flex;align-items:center;gap:10px;padding:8px 12px;background:var(--g1);border:1.5px solid var(--g2);border-radius:var(--r);">
            <i class="fa-solid fa-lock" style="color:var(--g4);font-size:12px;"></i>
            <span style="font-family:var(--mono);font-size:13px;font-weight:600;color:var(--g7);">{{ $processo->numero }}</span>
          </div>
          <span class="form-hint">O número é gerado automaticamente e não pode ser alterado.</span>
        </div>

        <div class="form-grid" style="gap:16px;">
          <div class="form-group span2">
            <label class="form-label" for="titulo">Título <span class="req">*</span></label>
            <input type="text" id="titulo" name="titulo" class="form-control {{ $errors->has('titulo')?'is-invalid':'' }}"
              value="{{ old('titulo',$processo->titulo) }}" required>
            @error('titulo')<span class="invalid-feedback">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label" for="secretaria">Secretaria / Setor</label>
            <input type="text" id="secretaria" name="secretaria" class="form-control" value="{{ old('secretaria',$processo->secretaria) }}">
          </div>
          <div class="form-group">
            <label class="form-label" for="valor_estimado">Valor Estimado (R$)</label>
            <div class="input-group">
              <span class="ig-text">R$</span>
              <input type="number" id="valor_estimado" name="valor_estimado" class="form-control"
                value="{{ old('valor_estimado',$processo->valor_estimado) }}" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group span2">
            <label class="form-label" for="objeto">Objeto</label>
            <textarea id="objeto" name="objeto" class="form-control">{{ old('objeto',$processo->objeto) }}</textarea>
          </div>
          <div class="form-group span2">
            <label class="form-label" for="descricao">Observações</label>
            <textarea id="descricao" name="descricao" class="form-control">{{ old('descricao',$processo->descricao) }}</textarea>
          </div>
          <div class="form-group">
            <label class="form-label" for="data_abertura">Data de Abertura</label>
            <input type="date" id="data_abertura" name="data_abertura" class="form-control" value="{{ old('data_abertura',$processo->data_abertura?->format('Y-m-d')) }}">
          </div>
          <div class="form-group">
            <label class="form-label" for="data_encerramento">Data de Encerramento</label>
            <input type="date" id="data_encerramento" name="data_encerramento" class="form-control" value="{{ old('data_encerramento',$processo->data_encerramento?->format('Y-m-d')) }}">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div style="display:flex;flex-direction:column;gap:14px;">
    <div class="card">
      <div class="card-header"><div class="card-title">Classificação</div></div>
      <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
        <div class="form-group">
          <label class="form-label" for="tipo">Tipo <span class="req">*</span></label>
          <select id="tipo" name="tipo" class="form-control" required>
            @foreach($tipos as $k=>$l)<option value="{{ $k }}" {{ old('tipo',$processo->tipo)==$k?'selected':'' }}>{{ $l }}</option>@endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label" for="status">Status <span class="req">*</span></label>
          <select id="status" name="status" class="form-control" required>
            @foreach($statusList as $k=>$l)<option value="{{ $k }}" {{ old('status',$processo->status)==$k?'selected':'' }}>{{ $l }}</option>@endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:10px;">
          <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
        </button>
        <a href="{{ route('processos.show',$processo) }}" class="btn btn-secondary" style="width:100%;justify-content:center;padding:10px;">Cancelar</a>
      </div>
    </div>
  </div>
</div>
</form>
@endsection
