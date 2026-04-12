<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','cargo','setor','role','ativo'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at'=>'datetime','password'=>'hashed','ativo'=>'boolean'];

    const ROLES = [
        'super_admin' => 'Super Admin',
        'admin'       => 'Admin',
        'setor'       => 'Setor',
        'usuario'     => 'Usuário',
        'auditor'     => 'Auditor',
    ];

    const ROLE_COLORS = [
        'super_admin' => 'danger',
        'admin'       => 'warning',
        'setor'       => 'info',
        'usuario'     => 'secondary',
        'auditor'     => 'success',
    ];

    // --- helpers de role ---
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return in_array($this->role, ['super_admin','admin']); }
    public function isAuditor(): bool    { return $this->role === 'auditor'; }
    public function isSetor(): bool      { return $this->role === 'setor'; }

    public function canManageUsers(): bool    { return $this->isSuperAdmin(); }
    public function canManageProcessos(): bool { return in_array($this->role, ['super_admin','admin','setor']); }
    public function canEdit(): bool           { return !$this->isAuditor(); }

    public function getRoleLabelAttribute(): string  { return self::ROLES[$this->role] ?? $this->role; }
    public function getRoleColorAttribute(): string  { return self::ROLE_COLORS[$this->role] ?? 'secondary'; }

    public function getInitialsAttribute(): string {
        $words = explode(' ', $this->name);
        return implode('', array_map(fn($w) => strtoupper(substr($w,0,1)), array_slice($words,0,2)));
    }

    // --- relationships ---
    public function processos()  { return $this->hasMany(Processo::class, 'criado_por'); }
    public function documentos() { return $this->hasMany(Documento::class, 'enviado_por'); }
    public function auditorias() { return $this->hasMany(Auditoria::class); }

    // --- e-mail de verificação em português ---
    public function sendEmailVerificationNotification(): void {
        $this->notify(new VerifyEmailNotification());
    }
}
