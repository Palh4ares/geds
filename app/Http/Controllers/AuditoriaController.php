<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('auditorias')) {
            $logs  = new LengthAwarePaginator([], 0, 20);
            $acoes = collect();
            return view('auditoria.index', compact('logs', 'acoes'));
        }

        $query = Auditoria::with('user')->orderByDesc('created_at');

        if ($request->filled('acao'))    $query->where('acao', $request->acao);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('data'))    $query->whereDate('created_at', $request->data);

        $logs  = $query->paginate(20)->withQueryString();
        $acoes = Auditoria::distinct()->orderBy('acao')->pluck('acao');

        return view('auditoria.index', compact('logs', 'acoes'));
    }
}
