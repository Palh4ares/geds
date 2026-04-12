<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Confirme seu e-mail — {{ $sistema }}</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0f2f7;color:#363a44;}
.wrap{max-width:580px;margin:36px auto;padding:0 16px 48px;}
.header{background:#0a1628;border-radius:10px 10px 0 0;padding:26px 36px;display:flex;align-items:center;gap:14px;}
.header-ico{width:40px;height:40px;background:#e05a00;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.header-ico svg{display:block;}
.header-title{color:#fff;font-size:15px;font-weight:600;}
.header-sub{color:#93bbdc;font-size:10px;letter-spacing:.8px;text-transform:uppercase;margin-top:2px;}
.body{background:#fff;padding:36px;border-left:1px solid #e2e5ed;border-right:1px solid #e2e5ed;}
.greeting{font-size:18px;font-weight:600;color:#1a1d23;margin-bottom:12px;}
.text{font-size:14px;line-height:1.75;color:#4a4f5c;margin-bottom:16px;}
.btn-wrap{text-align:center;margin:28px 0;}
.btn{display:inline-block;padding:14px 40px;background:#1e4d8c;color:#fff!important;text-decoration:none;border-radius:7px;font-size:15px;font-weight:600;}
.info-box{background:#eaf3fb;border-left:3px solid #1e4d8c;border-radius:6px;padding:14px 16px;margin:20px 0;}
.info-box p{font-size:13px;color:#1a3a6b;line-height:1.65;}
.url-box{background:#f8f9fc;border:1px solid #e2e5ed;border-radius:6px;padding:11px 14px;word-break:break-all;font-size:11.5px;color:#6b7080;font-family:'Courier New',monospace;}
.divider{border:none;border-top:1px solid #e2e5ed;margin:22px 0;}
.footer{background:#f8f9fc;border:1px solid #e2e5ed;border-top:none;border-radius:0 0 10px 10px;padding:18px 36px;}
.footer p{font-size:12px;color:#9298a8;line-height:1.65;}
.footer strong{color:#6b7080;}
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <div class="header-ico">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
      </svg>
    </div>
    <div>
      <div class="header-title">{{ $sistema }}</div>
      <div class="header-sub">Gestão Eletrônica de Documentos</div>
    </div>
  </div>

  <div class="body">
    <p class="greeting">Olá, {{ $nome }}!</p>
    <p class="text">
      Você foi cadastrado no sistema <strong>{{ $sistema }}</strong>.
      Para ativar seu acesso, confirme seu endereço de e-mail clicando no botão abaixo.
    </p>

    <div class="btn-wrap">
      <a href="{{ $url }}" class="btn">✓ &nbsp; Confirmar meu e-mail</a>
    </div>

    <div class="info-box">
      <p>⏱ &nbsp;Este link é válido por <strong>60 minutos</strong>.<br>
      Após expirar, solicite um novo link na tela de verificação.</p>
    </div>

    <p class="text" style="margin-bottom:8px;">Se o botão não funcionar, copie e cole o endereço abaixo no seu navegador:</p>
    <div class="url-box">{{ $url }}</div>

    <hr class="divider">

    <p class="text" style="font-size:13px;color:#9298a8;margin-bottom:0;">
      Se você não esperava receber este e-mail, ignore-o com segurança. Nenhuma ação será tomada.
    </p>
  </div>

  <div class="footer">
    <p>Enviado automaticamente pelo <strong>{{ $sistema }}</strong>. Por favor, não responda este e-mail.</p>
  </div>
</div>
</body>
</html>
