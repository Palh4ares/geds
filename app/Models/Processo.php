<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Processo extends Model
{
    use SoftDeletes;

    protected $table = 'processos';
    protected $fillable = [
        'numero','ano','sequencia','titulo','descricao',
        'secretaria','tipo','status','objeto',
        'valor_estimado','data_abertura','data_encerramento','criado_por',
    ];
    protected $casts = [
        'data_abertura'     => 'date',
        'data_encerramento' => 'date',
        'valor_estimado'    => 'decimal:2',
        'ano'               => 'integer',
        'sequencia'         => 'integer',
    ];

    const TIPOS = [
        'pregao_eletronico' => 'Pregão Eletrônico',
        'pregao_presencial' => 'Pregão Presencial',
        'concorrencia'      => 'Concorrência',
        'tomada_precos'     => 'Tomada de Preços',
        'convite'           => 'Convite',
        'dispensa'          => 'Dispensa de Licitação',
        'inexigibilidade'   => 'Inexigibilidade',
        'contrato'          => 'Contrato',
        'ata_registro'      => 'Ata de Registro de Preços',
        'outros'            => 'Outros',
    ];

    const STATUS = [
        'em_andamento' => 'Em Andamento',
        'em_analise'   => 'Em Análise',
        'aprovado'     => 'Aprovado',
        'finalizado'   => 'Finalizado',
        'cancelado'    => 'Cancelado',
    ];

    const STATUS_COLORS = [
        'em_andamento' => 'info',
        'em_analise'   => 'warning',
        'aprovado'     => 'success',
        'finalizado'   => 'secondary',
        'cancelado'    => 'danger',
    ];

    /**
     * Gera número sequencial global por ano: 001/2026, 002/2026...
     * Usa a coluna 'ano' para contagem — não depende de created_at.
     * lockForUpdate() evita duplicidade em requisições simultâneas.
     */
    public static function gerarNumero(): array
    {
        return DB::transaction(function () {
            $ano = now()->year;

            // Busca maior sequencia existente neste ano (incluindo excluídos)
            $maxSeq = self::withTrashed()
                ->where('ano', $ano)
                ->lockForUpdate()
                ->max('sequencia') ?? 0;

            $seq    = $maxSeq + 1;
            $numero = sprintf('%03d/%d', $seq, $ano);

            // Garantia extra contra colisão
            while (self::withTrashed()->where('numero', $numero)->exists()) {
                $seq++;
                $numero = sprintf('%03d/%d', $seq, $ano);
            }

            return ['numero' => $numero, 'ano' => $ano, 'sequencia' => $seq];
        });
    }

    // Accessors
    public function getTipoLabelAttribute(): string    { return self::TIPOS[$this->tipo]     ?? $this->tipo;   }
    public function getStatusLabelAttribute(): string  { return self::STATUS[$this->status]  ?? $this->status; }
    public function getStatusColorAttribute(): string  { return self::STATUS_COLORS[$this->status] ?? 'secondary'; }
    public function getTotalDocumentosAttribute(): int { return $this->documentos()->count(); }

    // Relationships
    public function criador()    { return $this->belongsTo(User::class, 'criado_por'); }
    public function documentos() { return $this->hasMany(Documento::class); }
    public function auditorias() { return $this->hasMany(Auditoria::class, 'modelo_id')
                                              ->where('modelo', 'Processo'); }
}
