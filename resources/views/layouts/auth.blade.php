<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — GED Licitações</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
  --blue-900:#0a1628;--blue-800:#0d2040;--blue-600:#1a3a6b;--blue-500:#1e4d8c;--blue-400:#2563a8;
  --blue-200:#93bbdc;--blue-100:#d0e5f5;--blue-50:#eaf3fb;
  --orange-600:#c84b00;--orange-500:#e05a00;--orange-400:#f47316;
  --gray-900:#1a1d23;--gray-700:#363a44;--gray-600:#4a4f5c;--gray-500:#6b7080;
  --gray-400:#9298a8;--gray-300:#c4c8d4;--gray-200:#e2e5ed;--gray-100:#f0f2f7;--gray-50:#f8f9fc;
  --danger:#b91c1c;--danger-bg:#fef2f2;
  --font:'IBM Plex Sans',sans-serif;--r:6px;--rl:10px;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--font);background:var(--blue-900);min-height:100vh;display:flex;align-items:stretch;overflow:hidden;}

/* Pattern overlay */
body::before{content:'';position:fixed;inset:0;
  background-image:linear-gradient(rgba(255,255,255,.02) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.02) 1px,transparent 1px);
  background-size:60px 60px;pointer-events:none;z-index:0;}
body::after{content:'';position:fixed;width:700px;height:700px;
  background:radial-gradient(circle,rgba(244,115,22,.07) 0%,transparent 70%);
  top:-200px;right:-200px;pointer-events:none;z-index:0;}

.auth-wrap{display:flex;width:100%;min-height:100vh;position:relative;z-index:1;}

/* Left panel */
.auth-left{flex:1;display:flex;align-items:center;justify-content:center;padding:48px;min-height:100vh;}
.auth-left-inner{max-width:460px;width:100%;}
.auth-logo{display:flex;align-items:center;gap:14px;margin-bottom:52px;}
.logo-ico{width:46px;height:46px;background:var(--orange-500);border-radius:var(--rl);display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;}
.logo-name{font-size:17px;font-weight:600;color:#fff;line-height:1.2;}
.logo-sub{font-size:10px;color:var(--blue-200);letter-spacing:1px;text-transform:uppercase;}
.auth-headline{font-size:34px;font-weight:300;color:#fff;line-height:1.3;margin-bottom:16px;}
.auth-headline strong{font-weight:600;color:var(--orange-400);}
.auth-desc{font-size:14px;color:rgba(255,255,255,.48);line-height:1.75;margin-bottom:44px;}
.auth-feat{list-style:none;display:flex;flex-direction:column;gap:12px;}
.auth-feat li{display:flex;align-items:flex-start;gap:10px;font-size:13.5px;color:rgba(255,255,255,.58);}
.auth-feat li i{color:var(--orange-400);font-size:11px;width:16px;text-align:center;margin-top:2px;flex-shrink:0;}

/* Right panel */
.auth-right{width:480px;background:var(--gray-50);display:flex;align-items:center;justify-content:center;padding:48px 40px;border-left:1px solid rgba(255,255,255,.05);}
.auth-form-wrap{width:100%;max-width:360px;}
.auth-form-title{font-size:22px;font-weight:600;color:var(--gray-900);margin-bottom:6px;}
.auth-form-sub{font-size:13px;color:var(--gray-500);margin-bottom:26px;}

/* Form elements */
.form-group{margin-bottom:15px;}
.form-label{display:block;font-size:11.5px;font-weight:600;color:var(--gray-700);margin-bottom:5px;letter-spacing:.2px;}
.form-control{width:100%;padding:10px 12px;border:1.5px solid var(--gray-300);border-radius:var(--r);font-size:13.5px;color:var(--gray-900);background:#fff;font-family:var(--font);transition:all .18s;}
.form-control:focus{outline:none;border-color:var(--blue-400);box-shadow:0 0 0 3px rgba(30,77,140,.11);}
.form-control.is-invalid{border-color:var(--danger);}
.form-control::placeholder{color:var(--gray-300);}
.invalid-feedback{font-size:11.5px;color:var(--danger);display:block;margin-top:4px;}
.checkbox-row{display:flex;align-items:center;gap:8px;}
.checkbox-row input{accent-color:var(--blue-500);width:15px;height:15px;cursor:pointer;}
.checkbox-row label{font-size:13px;color:var(--gray-600);cursor:pointer;}
.btn-primary{width:100%;padding:11px;background:var(--blue-500);color:#fff;border:none;border-radius:var(--r);font-size:14px;font-weight:600;cursor:pointer;font-family:var(--font);transition:all .18s;margin-top:10px;}
.btn-primary:hover{background:var(--blue-400);}

@media(max-width:900px){.auth-left{display:none;}.auth-right{width:100%;}}
</style>
</head>
<body>
<div class="auth-wrap">
  <!-- Left -->
  <div class="auth-left">
    <div class="auth-left-inner">
      <div class="auth-logo">
        <div class="logo-ico"><i class="fa-solid fa-scale-balanced"></i></div>
        <div><div class="logo-name">GED Licitações</div><div class="logo-sub">Gestão Eletrônica de Documentos</div></div>
      </div>
      <h1 class="auth-headline">Organização e<br><strong>transparência</strong><br>em licitações públicas</h1>
      <p class="auth-desc">Gerencie processos licitatórios e documentos digitalizados com segurança, rastreabilidade e conformidade legal.</p>
      <ul class="auth-feat">
        <li><i class="fa-solid fa-check"></i> Numeração automática e sequencial de processos</li>
        <li><i class="fa-solid fa-check"></i> Controle de acesso por perfis de usuário</li>
        <li><i class="fa-solid fa-check"></i> Upload e gestão de documentos em PDF</li>
        <li><i class="fa-solid fa-check"></i> Histórico completo de auditoria</li>
        <li><i class="fa-solid fa-check"></i> Busca avançada por número, secretaria e status</li>
        <li><i class="fa-solid fa-check"></i> Filtros por ano, tipo e secretaria</li>
      </ul>
    </div>
  </div>
  <!-- Right -->
  <div class="auth-right">
    <div class="auth-form-wrap">
      @yield('content')
    </div>
  </div>
</div>
</body>
</html>
