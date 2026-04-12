<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(15);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = User::ROLES;
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'cargo'    => 'nullable|string|max:100',
            'setor'    => 'nullable|string|max:100',
            'role'     => 'required|in:'.implode(',', array_keys(User::ROLES)),
            'password' => ['required','confirmed',Password::min(8)],
        ], [
            'name.required'      => 'O nome é obrigatório.',
            'email.unique'       => 'Este e-mail já está em uso.',
            'role.required'      => 'O perfil é obrigatório.',
            'password.confirmed' => 'As senhas não conferem.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'cargo'    => $validated['cargo'] ?? null,
            'setor'    => $validated['setor'] ?? null,
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
            'ativo'    => $request->boolean('ativo', true),
        ]);

        $user->sendEmailVerificationNotification();
        Auditoria::registrar('usuario.criado', "Usuário '{$user->name}' cadastrado", 'User', $user->id);

        return redirect()->route('admin.usuarios.index')
            ->with('success', "Usuário {$user->name} cadastrado e e-mail de verificação enviado.");
    }

    public function edit(User $usuario)
    {
        if ($usuario->isSuperAdmin() && !Auth::user()->isSuperAdmin()) abort(403);
        $roles = User::ROLES;
        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, User $usuario)
    {
        if ($usuario->isSuperAdmin() && !Auth::user()->isSuperAdmin()) abort(403);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$usuario->id,
            'cargo' => 'nullable|string|max:100',
            'setor' => 'nullable|string|max:100',
            'role'  => 'required|in:'.implode(',', array_keys(User::ROLES)),
        ]);

        $emailMudou = $validated['email'] !== $usuario->email;
        $usuario->update(array_merge($validated, ['ativo' => $request->boolean('ativo', true)]));

        if ($emailMudou) {
            $usuario->update(['email_verified_at' => null]);
            $usuario->sendEmailVerificationNotification();
        }

        Auditoria::registrar('usuario.editado', "Usuário '{$usuario->name}' editado", 'User', $usuario->id);
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === Auth::id()) return back()->with('error', 'Você não pode excluir sua própria conta.');
        if ($usuario->isSuperAdmin()) abort(403);
        $nome = $usuario->name;
        $usuario->delete();
        Auditoria::registrar('usuario.excluido', "Usuário '{$nome}' excluído");
        return redirect()->route('admin.usuarios.index')->with('success', "Usuário {$nome} excluído.");
    }

    public function reenviarVerificacao(User $usuario)
    {
        if ($usuario->hasVerifiedEmail()) return back()->with('info', 'Usuário já verificou o e-mail.');
        $usuario->sendEmailVerificationNotification();
        return back()->with('success', 'E-mail de verificação reenviado.');
    }
}
