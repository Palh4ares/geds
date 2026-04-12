@extends('layouts.app')
@section('title','Editar Documento')
@section('breadcrumb')
  <a href="{{ route('processos.show',$documento->processo_id) }}">Processo</a><span class="sep">›</span>
  <span class="cur">Editar Documento</span>
@endsection
@section('content')
<div class="pg-hdr">
  <div><h1 class="pg-title">Editar Documento</h1><p class="pg-sub">{{ $documento->arquivo_nome_original }}</p></div>
  <a href="{{ route('processos.show',$documento->processo_id) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>
<form method="POST" action="{{ route('documentos.update',$documento) }}">
@csrf @method('PUT')
<div style="max-width:680px;">
  <div class="card">
    <div class="card-header"><div class="card-title">Dados do Documento</div></div>
    <div class="card-body">
      <div class="form-grid" style="gap:16px;">
        <div class="form-group span2">
          <label class="form-label" for="nome">Nome <span class="req">*</span></label>
          <input type="text" id="nome" name="nome" class="form-control {{ $errors->has('nome')?'is-invalid':'' }}"
            value="{{ old('nome',$documento->nome) }}" required>
          @error('nome')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="form-group span2">
          <label class="form-label" for="descricao">Descrição</label>
          <textarea id="descricao" name="descricao" class="form-control">{{ old('descricao',$documento->descricao) }}</textarea>
        </div>
        <div class="form-group">
          <label class="form-label" for="tipo_documento">Tipo <span class="req">*</span></label>
          <select id="tipo_documento" name="tipo_documento" class="form-control" required>
            @foreach($tipos as $k=>$l)<option value="{{ $k }}" {{ old('tipo_documento',$documento->tipo_documento)==$k?'selected':'' }}>{{ $l }}</option>@endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label" for="versao">Versão</label>
          <input type="text" id="versao" name="versao" class="form-control mono" value="{{ old('versao',$documento->versao) }}">
        </div>
      </div>
    </div>
    <div class="card-footer" style="display:flex;gap:8px;">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
      <a href="{{ route('processos.show',$documento->processo_id) }}" class="btn btn-secondary">Cancelar</a>
    </div>
  </div>
</div>
</form>
@endsection
