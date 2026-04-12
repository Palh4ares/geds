<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\Processo;
use App\Models\Documento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $query = Processo::query();
        if ($user->isSetor() && $user->setor) {
            $query->where('secretaria', $user->setor);
        }

        $stats = [
            'total'        => (clone $query)->count(),
            'em_andamento' => (clone $query)->where('status', 'em_andamento')->count(),
            'em_analise'   => (clone $query)->where('status', 'em_analise')->count(),
            'aprovado'     => (clone $query)->where('status', 'aprovado')->count(),
            'finalizado'   => (clone $query)->where('status', 'finalizado')->count(),
            'cancelado'    => (clone $query)->where('status', 'cancelado')->count(),
            'documentos'   => Documento::count(),
            'usuarios'     => $user->isAdmin() ? User::count() : null,
        ];

        $processos_recentes = (clone $query)->with('criador')
            ->orderByDesc('created_at')->limit(6)->get();

        $documentos_recentes = Documento::with(['processo', 'enviador'])
            ->orderByDesc('created_at')->limit(5)->get();

        // Protegido: só busca auditorias se a tabela existir
        $historico_recente = collect();
        if (Schema::hasTable('auditorias')) {
            $historico_recente = Auditoria::with('user')
                ->orderByDesc('created_at')->limit(8)->get();
        }

        return view('dashboard', compact(
            'stats',
            'processos_recentes',
            'documentos_recentes',
            'historico_recente'
        ));
    }
}
