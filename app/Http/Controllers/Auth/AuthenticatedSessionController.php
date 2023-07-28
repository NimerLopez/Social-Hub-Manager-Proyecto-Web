<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }
    public function aut2fac()
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);
        return view('auth.verificar-2fa', compact('user'));
    }
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();
        $user = $request->user();
        // Verificar si la autenticación de doble factor está habilitada para el usuario
        if ($user->google2fa_enabled) {

            $this->generateGoogle2FA($user);
            Auth::guard('web')->logout(); //cierra sesion ya que no puede accerder
            session(['user_id' => $user->id]);
            // Si 2FA está habilitado, redireccionar al usuario a la página de verificación 2FA.
            return redirect()->route('2fact');
        }
        // Si 2FA no está habilitado, redireccionar al usuario a la página de inicio de la aplicación.
        return redirect(RouteServiceProvider::HOME);
    }
    private function generateGoogle2FA(User $user)
    {
        $googleAuthenticator = new GoogleAuthenticator();
        $secretKey = $googleAuthenticator->generateSecret();
        $qrCodeUrl = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($user['email'], $secretKey, "Trodo");
        $user->update([
            'google2fa_qr' => $qrCodeUrl,
            'google2fa_secret' => $secretKey,
        ]);
    }

    
    public function postVerifyTwoFactor(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            '2fa_token' => ['required', 'numeric'],
        ]);

        $userId = $request->input('user_id');
        $code = $request->input('2fa_token');
        $user = User::find($userId);
        dd($this->checkGoogleAuthenticatorCode($user->google2fa_secret, $code));
        if ($this->checkGoogleAuthenticatorCode($user->google2fa_secret, $code)) {
            return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Código 2FA verificado con éxito. Bienvenido.');
        }

        return redirect()->back()->withErrors(['2fa_token' => 'El código 2FA ingresado es incorrecto.']);
    }

    private function checkGoogleAuthenticatorCode($secret, $code) {
        $g = new GoogleAuthenticator();
        return $g->checkCode($secret, $code);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}