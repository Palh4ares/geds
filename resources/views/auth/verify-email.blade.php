@extends('layouts.auth')
@section('title','Verifique seu E-mail')
@section('content')
  <div style="text-align:center;margin-bottom:26px;">
    <div style="width:74px;height:74px;background:#eaf3fb;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#1e4d8c" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/>
      </svg>
    </div>
    <h2 class="auth-form-title" style="margin-bottom:7px;">Verifique seu e-mail</h2>
    <p class="auth-form-sub">
      Enviamos um link de confirmação para<br>
      @if(session('registered_email'))
        <strong style="color:var(--blue-500);">{{ session('registered_email') }}</strong>
      @else
        o seu e-mail cadastrado
      @endif
    </p>
  </div>

  @if(session('resent'))
    <div style="display:flex;align-items:flex-start;gap:9px;background:#e8f5ee;color:#1d7a4a;border-left:3px solid #1d7a4a;padding:11px 14px;border-radius:6px;font-size:13px;margin-bottom:18px;">
      <svg style="flex-shrink:0;margin-top:1px;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
      E-mail de confirmação reenviado com sucesso!
    </div>
  @endif

  @if($errors->any())
    <div style="background:#fef2f2;color:#b91c1c;border-left:3px solid #b91c1c;padding:10px 14px;border-radius:6px;font-size:13px;margin-bottom:18px;">
      {{ $errors->first() }}
    </div>
  @endif

  <div style="background:#f8f9fc;border:1px solid #e2e5ed;border-radius:8px;padding:16px 18px;margin-bottom:20px;">
    <p style="font-size:11px;font-weight:600;letter-spacing:.6px;text-transform:uppercase;color:#9298a8;margin-bottom:12px;">O que fazer agora</p>
    <div style="display:flex;flex-direction:column;gap:11px;">
      @foreach(['Abra o e-mail com assunto "Confirme seu e-mail — GED Licitações"','Clique no botão "Confirmar meu e-mail"','Você será redirecionado para a tela de login'] as $i=>$passo)
      <div style="display:flex;align-items:flex-start;gap:11px;">
        <div style="width:22px;height:22px;background:{{ $i<2?'#1e4d8c':'#1d7a4a' }};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">{{ $i<2?$i+1:'✓' }}</div>
        <p style="font-size:13px;color:#4a4f5c;line-height:1.5;padding-top:2px;">{{ $passo }}</p>
      </div>
      @endforeach
    </div>
  </div>

  <div style="display:flex;align-items:center;gap:8px;background:#fff5ec;border-left:3px solid #f47316;padding:10px 13px;border-radius:6px;margin-bottom:20px;">
    <svg style="flex-shrink:0;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#e05a00" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
    <p style="font-size:12.5px;color:#8a5d00;margin:0;">Link válido por <strong>60 minutos</strong>. Não encontrou? Verifique o <strong>spam</strong>.</p>
  </div>

  <form method="POST" action="{{ route('verification.resend') }}" id="resend-form">
    @csrf
    @if(session('registered_email'))
      <input type="hidden" name="email" value="{{ session('registered_email') }}">
    @endif
    <button type="submit" class="btn-primary" id="resend-btn" style="display:flex;align-items:center;justify-content:center;gap:8px;">
      <svg id="resend-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.49"/></svg>
      <span id="resend-txt">Reenviar e-mail de confirmação</span>
    </button>
  </form>

  <div style="text-align:center;margin-top:18px;">
    <a href="{{ route('login') }}" style="font-size:13px;color:#9298a8;text-decoration:none;">← Voltar para o login</a>
  </div>

<script>
(function(){
  const btn=document.getElementById('resend-btn'),txt=document.getElementById('resend-txt'),ico=document.getElementById('resend-icon');
  @if(session('resent')) startCd(); @endif
  document.getElementById('resend-form').addEventListener('submit',startCd);
  function startCd(){
    let s=60; btn.disabled=true; btn.style.opacity='.5'; btn.style.cursor='not-allowed'; ico.style.display='none';
    const iv=setInterval(function(){ s--;txt.textContent='Aguarde '+s+'s para reenviar';
      if(s<=0){clearInterval(iv);btn.disabled=false;btn.style.opacity='1';btn.style.cursor='pointer';ico.style.display='';txt.textContent='Reenviar e-mail de confirmação';}
    },1000);
  }
})();
</script>
@endsection
