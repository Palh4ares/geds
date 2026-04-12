<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Processo;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcessoController extends Controller
{
    public function index(Request $request)
    {
        $query = Processo::with('criador');

        // Restrição por setor
        $user = Auth::user();
        if ($user->isSetor() && $user->setor) {
            $query->where('secretaria', $user->setor);
        }

        if ($request->filled('busca')) {
            $b = $request->busca;
            $query->where(fn($q) => $q->where('numero','like',"%$b%")
                ->orWhere('titulo','like',"%$b%")
                ->orWhere('objeto','like',"%$b%")
                ->orWhere('descricao','like',"%$b%"));
        }
        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('secretaria')) $query->where('secretaria', $request->secretaria);
        if ($request->filled('tipo'))       $query->where('tipo', $request->tipo);
        if ($request->filled('ano'))        $query->where('ano', $request->ano);

        $processos  = $query->orderBy('created_at','desc')->paginate(15)->withQueryString();
        $tipos      = Processo::TIPOS;
        $statusList = Processo::STATUS;
        $secretarias = Processo::distinct()->orderBy('secretaria')->pluck('secretaria')->filter();
        $anos        = Processo::distinct()->orderByDesc('ano')->pluck('ano');

        return view('processos.index', compact('processos','tipos','statusList','secretarias','anos'));
    }

    public function create()
    {
        $tipos      = Processo::TIPOS;
        $statusList = Processo::STATUS;
        return view('processos.create', compact('tipos','statusList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'            => 'required|string|max:255',
            'descricao'         => 'nullable|string',
            'secretaria'        => 'nullable|string|max:150',
            'tipo'              => 'required|in:'.implode(',',array_keys(Processo::TIPOS)),
            'status'            => 'required|in:'.implode(',',array_keys(Processo::STATUS)),
            'objeto'            => 'nullable|string',
            'valor_estimado'    => 'nullable|numeric|min:0',
            'data_abertura'     => 'nullable|date',
            'data_encerramento' => 'nullable|date|after_or_equal:data_abertura',
        ], ['titulo.required'=>'O título é obrigatório.','tipo.required'=>'O tipo é obrigatório.']);

        $numeracao = Processo::gerarNumero();

        $processo = Processo::create(array_merge($validated, [
            'numero'     => $numeracao['numero'],
            'ano'        => $numeracao['ano'],
            'sequencia'  => $numeracao['sequencia'],
            'criado_por' => Auth::id(),
        ]));

        Auditoria::registrar('processo.criado', "Processo {$processo->numero} — {$processo->titulo} criado", 'Processo', $processo->id);

        return redirect()->route('processos.show', $processo)
            ->with('success', "Processo {$processo->numero} cadastrado com sucesso!");
    }

    public function show(Processo $processo)
    {
        $this->autorizarAcesso($processo);
        $processo->load(['criador','documentos.enviador']);
        $tiposDocumento = Documento::TIPOS;
        $historico = \App\Models\Auditoria::where('modelo','Processo')
            ->where('modelo_id', $processo->id)
            ->with('user')->orderByDesc('created_at')->get();
        return view('processos.show', compact('processo','tiposDocumento','historico'));
    }

    public function edit(Processo $processo)
    {
        $this->autorizarAcesso($processo);
        if (Auth::user()->isAuditor()) abort(403);
        $tipos = Processo::TIPOS; $statusList = Processo::STATUS;
        return view('processos.edit', compact('processo','tipos','statusList'));
    }

    public function update(Request $request, Processo $processo)
    {
        $this->autorizarAcesso($processo);
        if (Auth::user()->isAuditor()) abort(403);

        $validated = $request->validate([
            'titulo'            => 'required|string|max:255',
            'descricao'         => 'nullable|string',
            'secretaria'        => 'nullable|string|max:150',
            'tipo'              => 'required|in:'.implode(',',array_keys(Processo::TIPOS)),
            'status'            => 'required|in:'.implode(',',array_keys(Processo::STATUS)),
            'objeto'            => 'nullable|string',
            'valor_estimado'    => 'nullable|numeric|min:0',
            'data_abertura'     => 'nullable|date',
            'data_encerramento' => 'nullable|date|after_or_equal:data_abertura',
        ]);

        $statusAntigo = $processo->status;
        $processo->update($validated);

        $acao = $statusAntigo !== $processo->status ? 'processo.status' : 'processo.editado';
        Auditoria::registrar($acao, "Processo {$processo->numero} atualizado", 'Processo', $processo->id);

        return redirect()->route('processos.show', $processo)->with('success', 'Processo atualizado.');
    }

    public function destroy(Processo $processo)
    {
        if (!Auth::user()->isAdmin()) abort(403);
        $numero = $processo->numero;
        $processo->delete();
        Auditoria::registrar('processo.excluido', "Processo {$numero} excluído");
        return redirect()->route('processos.index')->with('success', "Processo {$numero} excluído.");
    }

    private function autorizarAcesso(Processo $processo): void
    {
        $user = Auth::user();
        if ($user->isSetor() && $user->setor && $processo->secretaria !== $user->setor) {
            abort(403, 'Este processo pertence a outro setor.');
        }
    }
}
