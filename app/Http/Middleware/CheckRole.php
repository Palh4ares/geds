<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) return redirect()->route('login');

        $user = Auth::user();

        if (!$user->ativo) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Sua conta está desativada. Entre em contato com o administrador.']);
        }

        // Super admin passa sempre
        if ($user->isSuperAdmin()) return $next($request);

        if (!empty($roles) && !in_array($user->role, $roles)) {
            abort(403, 'Acesso não autorizado para seu perfil.');
        }

        return $next($request);
    }
}
