<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','GED') — GED Licitações</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@300;400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
  --b9:#0a1628;--b8:#0d2040;--b6:#1a3a6b;--b5:#1e4d8c;--b4:#2563a8;--b3:#3b82c4;--b2:#93bbdc;--b1:#d0e5f5;--b0:#eaf3fb;
  --o6:#c84b00;--o5:#e05a00;--o4:#f47316;--o0:#fff5ec;
  --g9:#1a1d23;--g8:#252830;--g7:#363a44;--g6:#4a4f5c;--g5:#6b7080;--g4:#9298a8;--g3:#c4c8d4;--g2:#e2e5ed;--g1:#f0f2f7;--g0:#f8f9fc;
  --ok:#1d7a4a;--ok-bg:#e8f5ee;--warn:#8a5d00;--warn-bg:#fef7e0;--err:#b91c1c;--err-bg:#fef2f2;--info-bg:#eaf3fb;
  --sw:250px;--nh:58px;--r:6px;--rl:10px;
  --font:'IBM Plex Sans',sans-serif;--mono:'IBM Plex Mono',monospace;
  --sh:0 1px 3px rgba(0,0,0,.08);--sh2:0 2px 10px rgba(0,0,0,.10);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--font);background:var(--g1);color:var(--g8);display:flex;min-height:100vh;font-size:14px;line-height:1.5;}

/* ── SIDEBAR ── */
.sb{width:var(--sw);background:var(--b9);min-height:100vh;position:fixed;left:0;top:0;display:flex;flex-direction:column;z-index:100;}
.sb-brand{height:var(--nh);padding:0 18px;display:flex;align-items:center;gap:11px;border-bottom:1px solid rgba(255,255,255,.07);text-decoration:none;}
.brand-ico{width:32px;height:32px;background:var(--o5);border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:14px;color:#fff;flex-shrink:0;}
.brand-name{font-size:13px;font-weight:600;color:#fff;line-height:1.2;}
.brand-sub{font-size:9px;color:var(--b2);letter-spacing:.9px;text-transform:uppercase;}
.sb-nav{flex:1;padding:10px 0;overflow-y:auto;}
.sb-lbl{padding:10px 18px 3px;font-size:9.5px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.28);}
.sb-item{display:flex;align-items:center;gap:10px;padding:8px 18px;color:rgba(255,255,255,.62);font-size:13px;text-decoration:none;transition:all .15s;position:relative;cursor:pointer;border:none;background:none;width:100%;}
.sb-item:hover{color:#fff;background:rgba(255,255,255,.07);}
.sb-item.active{color:#fff;background:rgba(255,255,255,.11);font-weight:500;}
.sb-item.active::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--o4);border-radius:0 2px 2px 0;}
.sb-item i{width:16px;text-align:center;font-size:13px;opacity:.8;}
.sb-badge{margin-left:auto;background:var(--o5);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:99px;}
.sb-foot{padding:14px 18px;border-top:1px solid rgba(255,255,255,.07);}
.sb-user{display:flex;align-items:center;gap:9px;}
.sb-av{width:30px;height:30px;border-radius:50%;background:var(--b5);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;flex-shrink:0;}
.sb-uname{font-size:12.5px;font-weight:500;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.sb-urole{font-size:10px;color:rgba(255,255,255,.38);}

/* ── MAIN ── */
.main-wrap{margin-left:var(--sw);flex:1;display:flex;flex-direction:column;min-width:0;}
.navbar{height:var(--nh);background:#fff;border-bottom:1px solid var(--g2);display:flex;align-items:center;padding:0 24px;gap:12px;position:sticky;top:0;z-index:50;box-shadow:var(--sh);}
.nb-bc{flex:1;display:flex;align-items:center;gap:6px;font-size:12.5px;color:var(--g5);}
.nb-bc a{color:var(--g5);text-decoration:none;} .nb-bc a:hover{color:var(--b4);}
.nb-bc .cur{color:var(--g8);font-weight:500;} .nb-bc .sep{color:var(--g3);font-size:10px;}
.nb-acts{display:flex;align-items:center;gap:7px;}

/* ── BOTÕES ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r);font-size:13px;font-weight:500;cursor:pointer;text-decoration:none;transition:all .15s;font-family:var(--font);border:1.5px solid transparent;white-space:nowrap;}
.btn-primary{background:var(--b5);color:#fff;border-color:var(--b4);}         .btn-primary:hover{background:var(--b4);}
.btn-orange{background:var(--o5);color:#fff;border-color:var(--o6);}           .btn-orange:hover{background:var(--o6);}
.btn-secondary{background:#fff;color:var(--g7);border-color:var(--g3);}        .btn-secondary:hover{background:var(--g1);}
.btn-danger{background:var(--err);color:#fff;border-color:var(--err);}          .btn-danger:hover{opacity:.88;}
.btn-success{background:var(--ok);color:#fff;border-color:var(--ok);}           .btn-success:hover{opacity:.88;}
.btn-sm{padding:5px 11px;font-size:12px;}
.btn-icon{display:inline-flex;align-items:center;justify-content:center;width:30px;height:30px;border-radius:var(--r);border:1px solid var(--g2);background:#fff;color:var(--g6);cursor:pointer;font-size:12px;transition:all .15s;text-decoration:none;}
.btn-icon:hover{background:var(--g1);color:var(--b5);border-color:var(--g3);}
.btn-icon.danger:hover{background:var(--err-bg);color:var(--err);border-color:var(--err);}

/* ── USER DROPDOWN ── */
.nb-user-wrap{position:relative;}
.nb-user{display:flex;align-items:center;gap:8px;padding:4px 12px 4px 4px;border:1px solid var(--g2);border-radius:99px;background:var(--g0);cursor:pointer;user-select:none;}
.nb-user:hover{border-color:var(--g3);background:var(--g1);}
.nb-av{width:28px;height:28px;border-radius:50%;background:var(--b5);display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;color:#fff;}
.nb-uname{font-size:12.5px;font-weight:500;color:var(--g7);}
.nb-caret{font-size:10px;color:var(--g5);}
.dropdown-menu{position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid var(--g2);border-radius:var(--rl);box-shadow:var(--sh2);min-width:200px;z-index:200;display:none;overflow:hidden;}
.dropdown-menu.show{display:block;}
.dd-header{padding:12px 16px;border-bottom:1px solid var(--g1);}
.dd-name{font-size:13px;font-weight:600;color:var(--g9);}
.dd-role{font-size:11px;margin-top:1px;}
.dd-item{display:flex;align-items:center;gap:9px;padding:9px 16px;font-size:13px;color:var(--g7);text-decoration:none;transition:background .12s;cursor:pointer;border:none;background:none;width:100%;font-family:var(--font);}
.dd-item:hover{background:var(--g0);color:var(--b5);}
.dd-item i{width:14px;text-align:center;font-size:12px;color:var(--g4);}
.dd-item:hover i{color:var(--b4);}
.dd-divider{border:none;border-top:1px solid var(--g1);margin:4px 0;}

/* ── CONTENT ── */
.main-content{flex:1;padding:24px;}
.pg-hdr{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:22px;gap:16px;}
.pg-title{font-size:21px;font-weight:600;color:var(--g9);margin-bottom:2px;}
.pg-sub{font-size:12.5px;color:var(--g5);}
.pg-acts{display:flex;gap:8px;flex-shrink:0;}

/* ── CARDS ── */
.card{background:#fff;border:1px solid var(--g2);border-radius:var(--rl);box-shadow:var(--sh);}
.card-header{padding:14px 20px;border-bottom:1px solid var(--g2);display:flex;align-items:center;justify-content:space-between;gap:12px;}
.card-title{font-size:14.5px;font-weight:600;color:var(--g8);}
.card-subtitle{font-size:11.5px;color:var(--g5);margin-top:1px;}
.card-body{padding:20px;}
.card-footer{padding:12px 20px;border-top:1px solid var(--g2);background:var(--g0);border-radius:0 0 var(--rl) var(--rl);}

/* ── STAT CARDS ── */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;}
.sc{background:#fff;border:1px solid var(--g2);border-radius:var(--rl);padding:18px;display:flex;align-items:flex-start;gap:13px;box-shadow:var(--sh);transition:all .2s;}
.sc:hover{box-shadow:var(--sh2);transform:translateY(-1px);}
.sc-ico{width:42px;height:42px;border-radius:var(--r);display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;}
.sc-ico.bl{background:var(--b0);color:var(--b5);}  .sc-ico.or{background:#fff5ec;color:var(--o5);}
.sc-ico.gn{background:var(--ok-bg);color:var(--ok);} .sc-ico.gy{background:var(--g1);color:var(--g6);}
.sc-ico.rd{background:var(--err-bg);color:var(--err);} .sc-ico.wn{background:var(--warn-bg);color:var(--warn);}
.sc-lbl{font-size:11px;color:var(--g5);margin-bottom:3px;}
.sc-val{font-size:24px;font-weight:600;color:var(--g9);font-family:var(--mono);}

/* ── TABLE ── */
.table-wrap{overflow-x:auto;}
table.dt{width:100%;border-collapse:collapse;font-size:13.5px;}
.dt thead th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:600;letter-spacing:.5px;text-transform:uppercase;color:var(--g5);background:var(--g0);border-bottom:1px solid var(--g2);white-space:nowrap;}
.dt tbody tr{border-bottom:1px solid var(--g1);transition:background .12s;}
.dt tbody tr:last-child{border-bottom:none;}
.dt tbody tr:hover td{background:var(--g0);}
.dt td{padding:11px 14px;color:var(--g7);vertical-align:middle;}
.td-bold{font-weight:500;color:var(--g9);}  .td-mono{font-family:var(--mono);font-size:12.5px;}
.td-muted{color:var(--g4);font-size:11.5px;}

/* ── BADGES ── */
.badge{display:inline-flex;align-items:center;padding:2px 9px;border-radius:99px;font-size:11px;font-weight:500;white-space:nowrap;}
.badge-info{background:var(--b0);color:var(--b5);}       .badge-success{background:var(--ok-bg);color:var(--ok);}
.badge-warning{background:var(--warn-bg);color:var(--warn);} .badge-danger{background:var(--err-bg);color:var(--err);}
.badge-secondary{background:var(--g1);color:var(--g6);}   .badge-orange{background:#fff5ec;color:var(--o6);}

/* ── FORMS ── */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px;}
.form-grid.g3{grid-template-columns:1fr 1fr 1fr;}
.form-grid.g1{grid-template-columns:1fr;}
.span2{grid-column:span 2;} .span3{grid-column:span 3;}
.form-group{display:flex;flex-direction:column;gap:5px;}
.form-label{font-size:11.5px;font-weight:600;color:var(--g7);letter-spacing:.2px;}
.form-label .req{color:var(--err);margin-left:2px;}
.form-control{padding:8px 11px;border:1.5px solid var(--g3);border-radius:var(--r);font-size:13.5px;color:var(--g8);background:#fff;font-family:var(--font);transition:all .18s;width:100%;}
.form-control:focus{outline:none;border-color:var(--b4);box-shadow:0 0 0 3px rgba(30,77,140,.11);}
.form-control.is-invalid{border-color:var(--err);}
.form-control::placeholder{color:var(--g4);}
textarea.form-control{resize:vertical;min-height:80px;}
.form-hint{font-size:11px;color:var(--g4);}
.invalid-feedback{font-size:11.5px;color:var(--err);display:block;}
.input-group{display:flex;}
.ig-text{padding:8px 11px;background:var(--g1);border:1.5px solid var(--g3);border-right:none;border-radius:var(--r) 0 0 var(--r);font-size:13px;color:var(--g6);}
.input-group .form-control{border-radius:0 var(--r) var(--r) 0;}

/* ── SEARCH BAR ── */
.srch{display:flex;gap:8px;margin-bottom:18px;flex-wrap:wrap;align-items:flex-end;}
.si{position:relative;} .si i{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--g4);font-size:12px;}
.si .form-control{padding-left:32px;}

/* ── ALERTS ── */
.alert{display:flex;align-items:flex-start;gap:10px;padding:11px 15px;border-radius:var(--r);font-size:13.5px;margin-bottom:18px;border-left:3px solid;}
.alert-success{background:var(--ok-bg);color:var(--ok);border-color:var(--ok);}
.alert-danger{background:var(--err-bg);color:var(--err);border-color:var(--err);}
.alert-warning{background:var(--warn-bg);color:var(--warn);border-color:var(--warn);}
.alert-info{background:var(--b0);color:var(--b5);border-color:var(--b5);}

/* ── DETAIL ── */
.det-grid{display:grid;grid-template-columns:1fr 1fr;}
.det-item{padding:12px 18px;border-bottom:1px solid var(--g1);}
.det-item:nth-child(odd){border-right:1px solid var(--g1);}
.det-lbl{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.6px;color:var(--g4);margin-bottom:3px;}
.det-val{font-size:13.5px;color:var(--g8);}

/* ── FILE DROP ── */
.file-drop{border:2px dashed var(--g3);border-radius:var(--rl);padding:28px 20px;text-align:center;cursor:pointer;transition:all .18s;background:var(--g0);}
.file-drop:hover,.file-drop.drag-over{border-color:var(--b4);background:var(--b0);}
.file-drop i{font-size:28px;color:var(--g3);display:block;margin-bottom:8px;}
.file-drop p{font-size:13px;color:var(--g5);} .file-drop input[type=file]{display:none;}

/* ── PAGINATION ── */
.pag-wrap{display:flex;align-items:center;justify-content:space-between;padding:11px 18px;border-top:1px solid var(--g2);font-size:12.5px;color:var(--g5);}

/* ── EMPTY STATE ── */
.empty{text-align:center;padding:44px 20px;}
.empty i{font-size:38px;color:var(--g3);display:block;margin-bottom:10px;}
.empty h4{font-size:14.5px;font-weight:600;color:var(--g6);margin-bottom:5px;}
.empty p{font-size:12.5px;color:var(--g4);}

/* ── MISC ── */
a{text-decoration:none;} .text-muted{color:var(--g5);} .mono{font-family:var(--mono);}
.d-flex{display:flex;} .gap-2{gap:8px;} .gap-1{gap:4px;} .align-center{align-items:center;}
.mt-2{margin-top:8px;} .mt-3{margin-top:16px;} .mb-3{margin-bottom:16px;}
.grid2{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.grid-main{display:grid;grid-template-columns:2fr 1fr;gap:18px;align-items:start;}

@media(max-width:1024px){.stats-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:768px){
  .sb{transform:translateX(-100%);} .main-wrap{margin-left:0;}
  .stats-grid{grid-template-columns:repeat(2,1fr);}
  .form-grid,.form-grid.g3{grid-template-columns:1fr;}
  .span2,.span3{grid-column:span 1;}
  .grid-main{grid-template-columns:1fr;}
  .det-grid{grid-template-columns:1fr;}
  .det-item:nth-child(odd){border-right:none;}
}
</style>
@yield('styles')
</head>
<body>

<aside class="sb">
  <a href="{{ route('dashboard') }}" class="sb-brand">
    <div class="brand-ico"><i class="fa-solid fa-scale-balanced"></i></div>
    <div><div class="brand-name">GED Licitações</div><div class="brand-sub">Gestão Eletrônica</div></div>
  </a>
  <nav class="sb-nav">
    <div class="sb-lbl">Principal</div>
    <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <i class="fa-solid fa-gauge-high"></i> Painel de Controle
    </a>

    <div class="sb-lbl">Processos</div>
    <a href="{{ route('processos.index') }}" class="sb-item {{ request()->routeIs('processos.index') ? 'active' : '' }}">
      <i class="fa-solid fa-folder-open"></i> Todos os Processos
    </a>
    @if(Auth::user()->canManageProcessos())
    <a href="{{ route('processos.create') }}" class="sb-item {{ request()->routeIs('processos.create') ? 'active' : '' }}">
      <i class="fa-solid fa-circle-plus"></i> Novo Processo
    </a>
    @endif

    <div class="sb-lbl">Documentos</div>
    @if(Auth::user()->canEdit())
    <a href="{{ route('documentos.create') }}" class="sb-item {{ request()->routeIs('documentos.create') ? 'active' : '' }}">
      <i class="fa-solid fa-file-arrow-up"></i> Enviar Documento
    </a>
    @endif

    @if(Auth::user()->isAdmin())
    <div class="sb-lbl">Administração</div>
    <a href="{{ route('auditoria.index') }}" class="sb-item {{ request()->routeIs('auditoria.*') ? 'active' : '' }}">
      <i class="fa-solid fa-clock-rotate-left"></i> Auditoria
    </a>
    @endif

    @if(Auth::user()->canManageUsers())
    <a href="{{ route('admin.usuarios.index') }}" class="sb-item {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
      <i class="fa-solid fa-users-gear"></i> Usuários
    </a>
    @endif
  </nav>

  <div class="sb-foot">
    <div class="sb-user">
      <div class="sb-av">{{ Auth::user()->initials }}</div>
      <div style="flex:1;overflow:hidden;">
        <div class="sb-uname">{{ Auth::user()->name }}</div>
        <div class="sb-urole">{{ Auth::user()->role_label }}</div>
      </div>
    </div>
  </div>
</aside>

<div class="main-wrap">
  <nav class="navbar">
    <div class="nb-bc">@yield('breadcrumb','<span class="cur">Painel</span>')</div>
    <div class="nb-acts">
      @yield('navbar-actions')

      {{-- User dropdown --}}
      <div class="nb-user-wrap">
        <div class="nb-user" id="userMenuBtn">
          <div class="nb-av">{{ Auth::user()->initials }}</div>
          <span class="nb-uname">{{ Str::words(Auth::user()->name, 1, '') }}</span>
          <i class="fa-solid fa-chevron-down nb-caret"></i>
        </div>
        <div class="dropdown-menu" id="userMenu">
          <div class="dd-header">
            <div class="dd-name">{{ Auth::user()->name }}</div>
            <div class="dd-role">
              <span class="badge badge-{{ Auth::user()->role_color }}">{{ Auth::user()->role_label }}</span>
            </div>
          </div>
          <a href="{{ route('perfil.show') }}" class="dd-item">
            <i class="fa-solid fa-user"></i> Meu Perfil
          </a>
          <a href="{{ route('perfil.show') }}#senha" class="dd-item">
            <i class="fa-solid fa-lock"></i> Alterar Senha
          </a>
          <a href="{{ route('perfil.show') }}#email" class="dd-item">
            <i class="fa-solid fa-envelope"></i> Alterar E-mail
          </a>
          <hr class="dd-divider">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dd-item" style="color:var(--err);">
              <i class="fa-solid fa-arrow-right-from-bracket" style="color:var(--err);"></i> Sair do Sistema
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>

  <main class="main-content">
    @foreach(['success','error','info','warning'] as $t)
      @if(session($t))
        <div class="alert alert-{{ $t === 'error' ? 'danger' : $t }}">
          <i class="fa-solid fa-{{ $t === 'success' ? 'circle-check' : ($t === 'error' ? 'circle-exclamation' : 'circle-info') }}"></i>
          <span>{{ session($t) }}</span>
        </div>
      @endif
    @endforeach

    @yield('content')
  </main>
</div>

<script>
// Dropdown
const btn = document.getElementById('userMenuBtn');
const menu = document.getElementById('userMenu');
btn?.addEventListener('click', e => { e.stopPropagation(); menu.classList.toggle('show'); });
document.addEventListener('click', () => menu?.classList.remove('show'));

// Auto-dismiss alerts
setTimeout(() => {
  document.querySelectorAll('.alert').forEach(el => {
    el.style.transition = 'opacity .4s'; el.style.opacity = '0';
    setTimeout(() => el.remove(), 400);
  });
}, 5000);

// File drop
document.querySelectorAll('.file-drop').forEach(drop => {
  const input = drop.querySelector('input[type=file]');
  drop.addEventListener('dragover', e => { e.preventDefault(); drop.classList.add('drag-over'); });
  drop.addEventListener('dragleave', () => drop.classList.remove('drag-over'));
  drop.addEventListener('drop', e => {
    e.preventDefault(); drop.classList.remove('drag-over');
    if (input && e.dataTransfer.files.length) {
      input.files = e.dataTransfer.files;
      const p = drop.querySelector('p');
      if (p) p.innerHTML = '<strong>'+input.files[0].name+'</strong> selecionado';
    }
  });
  input?.addEventListener('change', () => {
    const p = drop.querySelector('p');
    if (p && input.files.length) p.innerHTML = '<strong>'+input.files[0].name+'</strong> selecionado';
  });
});
</script>
@yield('scripts')
</body>
</html>
