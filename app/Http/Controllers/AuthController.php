<?php
namespace App\Http\Controllers;

use App\Models\Auditoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->ativo) {
                Auth::logout();
                return back()->withErrors(['email' => 'Sua conta está desativada.'])->onlyInput('email');
            }

            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors([
                    'email' => 'Confirme seu e-mail antes de acessar. Verifique sua caixa de entrada.',
                ])->onlyInput('email');
            }

            Auditoria::registrar('login', "Login realizado por {$user->name}");
            return redirect()->intended(route('dashboard'))->with('success', "Bem-vindo(a), {$user->name}!");
        }

        return back()->withErrors(['email' => 'E-mail ou senha incorretos.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auditoria::registrar('logout', 'Logout realizado');
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Você saiu do sistema com segurança.');
    }

    // --- Verificação de e-mail ---
    public function verificationNotice()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) return redirect()->route('dashboard');
        return view('auth.verify-email');
    }

    public function verificationVerify(Request $request, $id, $hash)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route('verification.notice')
                ->withErrors(['link' => 'O link expirou ou é inválido. Solicite um novo abaixo.']);
        }

        $user = User::findOrFail($id);

        if (!hash_equals(sha1($user->getEmailForVerification()), (string)$hash)) {
            return redirect()->route('verification.notice')
                ->withErrors(['link' => 'O link de verificação é inválido.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')
                ->with('success', 'E-mail já verificado. Faça o login.');
        }

        $user->markEmailAsVerified();

        // NÃO faz login automático — redireciona para login
        return redirect()->route('login')
            ->with('success', '✅ E-mail confirmado com sucesso! Agora faça o login para acessar o sistema.');
    }

    public function verificationResend(Request $request)
    {
        $email = $request->input('email');
        if (!$email) return back()->withErrors(['email' => 'Informe o e-mail.']);

        $user = User::where('email', $email)->first();
        if (!$user) return back()->with('resent', true); // não revela se existe

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'E-mail já verificado. Faça o login.');
        }

        $user->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
