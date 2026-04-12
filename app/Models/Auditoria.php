<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;

class Auditoria extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'acao', 'modelo', 'modelo_id', 'descricao', 'ip',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Registra uma ação no log de auditoria.
     * Seguro: não quebra se a tabela ainda não existir.
     */
    public static function registrar(
        string $acao,
        string $descricao,
        ?string $modelo = null,
        ?int $modeloId = null
    ): void {
        try {
            if (!Schema::hasTable('auditorias')) {
                return;
            }

            static::create([
                'user_id'   => Auth::id(),
                'acao'      => $acao,
                'modelo'    => $modelo,
                'modelo_id' => $modeloId,
                'descricao' => $descricao,
                'ip'        => Request::ip(),
            ]);
        } catch (\Throwable $e) {
            // Nunca deixa a auditoria derrubar o fluxo principal
            logger()->warning("Auditoria falhou [{$acao}]: " . $e->getMessage());
        }
    }

    /**
     * Retorna o label legível de uma ação.
     */
    public static function labelAcao(string $acao): string
    {
        return [
            'login'              => 'Login realizado',
            'logout'             => 'Logout realizado',
            'processo.criado'    => 'Processo criado',
            'processo.editado'   => 'Processo editado',
            'processo.excluido'  => 'Processo excluído',
            'processo.status'    => 'Status alterado',
            'documento.upload'   => 'Documento enviado',
            'documento.excluido' => 'Documento excluído',
            'usuario.criado'     => 'Usuário cadastrado',
            'usuario.editado'    => 'Usuário editado',
            'usuario.excluido'   => 'Usuário excluído',
            'perfil.senha'       => 'Senha alterada',
            'perfil.email'       => 'E-mail alterado',
        ][$acao] ?? $acao;
    }
}
