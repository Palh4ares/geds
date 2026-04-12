@extends('layouts.app')
@section('title','404 — Não encontrado')
@section('content')
<div style="display:flex;align-items:center;justify-content:center;min-height:60vh;">
  <div style="text-align:center;max-width:420px;">
    <div style="font-size:72px;font-weight:700;color:var(--g2);line-height:1;margin-bottom:12px;font-family:var(--mono);">404</div>
    <h2 style="font-size:20px;font-weight:600;color:var(--g8);margin-bottom:8px;">Página não encontrada</h2>
    <p style="font-size:14px;color:var(--g5);margin-bottom:24px;">O recurso que você procura não existe ou foi removido.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fa-solid fa-house"></i> Voltar ao painel</a>
  </div>
</div>
@endsection
