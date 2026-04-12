<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Documento;
use App\Models\Processo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoController extends Controller
{
    public function create(Request $request)
    {
        $processo_id = $request->get('processo_id');
        $processo    = $processo_id ? Processo::findOrFail($processo_id) : null;
        $processos   = Processo::orderByDesc('created_at')->get();
        $tipos       = Documento::TIPOS;
        return view('documentos.create', compact('processo','processos','tipos'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAuditor()) abort(403);

        $validated = $request->validate([
            'processo_id'  => 'required|exists:processos,id',
            'nome'         => 'required|string|max:255',
            'descricao'    => 'nullable|string',
            'tipo_documento'=> 'required|in:'.implode(',',array_keys(Documento::TIPOS)),
            'arquivo'      => 'required|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg',
            'versao'       => 'nullable|string|max:20',
        ], [
            'arquivo.required' => 'Selecione um arquivo.',
            'arquivo.max'      => 'Arquivo deve ter no máximo 20MB.',
            'arquivo.mimes'    => 'Formatos: PDF, DOC, DOCX, XLS, XLSX, PNG, JPG.',
        ]);

        $arquivo  = $request->file('arquivo');
        $processo = Processo::findOrFail($validated['processo_id']);

        // Nome padronizado: NUMERO-PROCESSO_tipo_uuid.ext
        $nomeBase   = Str::slug($processo->numero.'_'.$validated['tipo_documento']);
        $nomeArquivo= $nomeBase.'_'.Str::uuid().'.'.$arquivo->getClientOriginalExtension();
        $path       = $arquivo->storeAs('processos/'.$processo->id.'/documentos', $nomeArquivo, 'local');

        $doc = Documento::create([
            'processo_id'         => $processo->id,
            'nome'                => $validated['nome'],
            'descricao'           => $validated['descricao'] ?? null,
            'tipo_documento'      => $validated['tipo_documento'],
            'arquivo_path'        => $path,
            'arquivo_nome_original'=> $arquivo->getClientOriginalName(),
            'arquivo_tamanho'     => $arquivo->getSize(),
            'arquivo_mime'        => $arquivo->getMimeType(),
            'versao'              => $validated['versao'] ?? '1.0',
            'enviado_por'         => Auth::id(),
        ]);

        Auditoria::registrar('documento.upload',
            "Documento '{$doc->nome}' enviado para processo {$processo->numero}",
            'Processo', $processo->id);

        return redirect()->route('processos.show', $processo)->with('success', 'Documento enviado com sucesso!');
    }

    public function edit(Documento $documento)
    {
        if (Auth::user()->isAuditor()) abort(403);
        $tipos = Documento::TIPOS;
        return view('documentos.edit', compact('documento','tipos'));
    }

    public function update(Request $request, Documento $documento)
    {
        if (Auth::user()->isAuditor()) abort(403);
        $validated = $request->validate([
            'nome'          => 'required|string|max:255',
            'descricao'     => 'nullable|string',
            'tipo_documento'=> 'required|in:'.implode(',',array_keys(Documento::TIPOS)),
            'versao'        => 'nullable|string|max:20',
        ]);
        $documento->update($validated);
        return redirect()->route('processos.show', $documento->processo_id)->with('success', 'Documento atualizado.');
    }

    public function destroy(Documento $documento)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $processo_id = $documento->processo_id;
        $nome        = $documento->nome;
        if (Storage::disk('local')->exists($documento->arquivo_path)) {
            Storage::disk('local')->delete($documento->arquivo_path);
        }
        $documento->delete();
        Auditoria::registrar('documento.excluido', "Documento '{$nome}' excluído", 'Processo', $processo_id);
        return redirect()->route('processos.show', $processo_id)->with('success', 'Documento excluído.');
    }

    public function download(Documento $documento)
    {
        if (!Storage::disk('local')->exists($documento->arquivo_path)) abort(404);
        return Storage::disk('local')->download($documento->arquivo_path, $documento->arquivo_nome_original);
    }

    public function visualizar(Documento $documento)
    {
        if (!$documento->isPdf()) return redirect()->route('documentos.download', $documento);
        if (!Storage::disk('local')->exists($documento->arquivo_path)) abort(404);
        $conteudo = Storage::disk('local')->get($documento->arquivo_path);
        return response($conteudo, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$documento->arquivo_nome_original.'"',
        ]);
    }
}
