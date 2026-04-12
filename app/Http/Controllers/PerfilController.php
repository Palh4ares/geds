<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function show()
    {
        return view('perfil.show', ['user' => Auth::user()]);
    }

    public function alterarSenha(Request $request)
    {
        $request->validate([
            'senha_atual'  => 'required',
            'password'     => ['required','confirmed',Password::min(8)],
        ], [
            'senha_atual.required' => 'Informe a senha atual.',
            'password.confirmed'   => 'As novas senhas não conferem.',
            'password.min'         => 'A nova senha deve ter pelo menos 8 caracteres.',
        ]);

        if (!Hash::check($request->senha_atual, Auth::user()->password)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        Auth::user()->update(['password' => Hash::make($request->password)]);
        Auditoria::registrar('perfil.senha', 'Senha alterada pelo próprio usuário');

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    public function alterarEmail(Request $request)
    {
        $request->validate([
            'email'       => 'required|email|unique:users,email,'.Auth::id(),
            'senha_atual' => 'required',
        ], [
            'email.unique'         => 'Este e-mail já está em uso.',
            'senha_atual.required' => 'Informe sua senha para confirmar.',
        ]);

        if (!Hash::check($request->senha_atual, Auth::user()->password)) {
            return back()->withErrors(['senha_atual' => 'Senha incorreta.']);
        }

        $emailAntigo = Auth::user()->email;
        Auth::user()->update(['email' => $request->email, 'email_verified_at' => null]);
        Auth::user()->sendEmailVerificationNotification();

        Auditoria::registrar('perfil.email', "E-mail alterado de {$emailAntigo} para {$request->email}");

        return back()->with('success', 'E-mail alterado! Um link de verificação foi enviado para o novo endereço.');
    }
}
