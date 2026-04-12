<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'processo_id','nome','descricao','tipo_documento',
        'arquivo_path','arquivo_nome_original','arquivo_tamanho',
        'arquivo_mime','versao','enviado_por',
    ];

    const TIPOS = [
        'edital'       => 'Edital',
        'contrato'     => 'Contrato',
        'proposta'     => 'Proposta',
        'ata'          => 'Ata',
        'habilitacao'  => 'Habilitação',
        'recurso'      => 'Recurso',
        'parecer'      => 'Parecer Jurídico',
        'nota_empenho' => 'Nota de Empenho',
        'publicacao'   => 'Publicação',
        'outros'       => 'Outros',
    ];

    public function getTipoLabelAttribute(): string {
        return self::TIPOS[$this->tipo_documento] ?? $this->tipo_documento;
    }

    public function getTamanhoFormatadoAttribute(): string {
        $b = $this->arquivo_tamanho;
        if ($b >= 1048576) return number_format($b/1048576, 1).' MB';
        if ($b >= 1024)    return number_format($b/1024, 0).' KB';
        return $b.' B';
    }

    public function isPdf(): bool { return str_contains($this->arquivo_mime, 'pdf'); }

    public function processo()  { return $this->belongsTo(Processo::class); }
    public function enviador()  { return $this->belongsTo(User::class, 'enviado_por'); }
}
