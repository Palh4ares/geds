<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProcessoController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\Admin\UsuarioController;

Route::get('/', fn() => redirect()->route('login'));

// ── Visitantes ────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ── Verificação de e-mail (sem autenticação) ──────────────────────────────────
Route::get('/email/verify',
    [AuthController::class, 'verificationNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}',
    [AuthController::class, 'verificationVerify'])->middleware('signed')->name('verification.verify');
Route::post('/email/resend',
    [AuthController::class, 'verificationResend'])->middleware('throttle:6,1')->name('verification.resend');

// ── Autenticado + e-mail verificado ──────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil do usuário (qualquer autenticado)
    Route::get('/perfil',          [PerfilController::class, 'show'])->name('perfil.show');
    Route::post('/perfil/senha',   [PerfilController::class, 'alterarSenha'])->name('perfil.senha');
    Route::post('/perfil/email',   [PerfilController::class, 'alterarEmail'])->name('perfil.email');

    // Processos
    Route::resource('processos', ProcessoController::class);
    Route::get('/documentos/{documento}/download',
        [DocumentoController::class, 'download'])->name('documentos.download');
    Route::get('/documentos/{documento}/visualizar',
        [DocumentoController::class, 'visualizar'])->name('documentos.visualizar');
    Route::resource('documentos', DocumentoController::class)->except(['index','show']);

    // Auditoria (admin+)
    Route::get('/auditoria', [AuditoriaController::class, 'index'])
        ->middleware('role:super_admin,admin')
        ->name('auditoria.index');

    // Gestão de usuários (super_admin)
    Route::prefix('admin')->name('admin.')->middleware('role:super_admin')->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::post('usuarios/{usuario}/reenviar',
            [UsuarioController::class, 'reenviarVerificacao'])->name('usuarios.reenviar');
    });
});
