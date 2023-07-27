<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FALaravel\Google2FA;
use App\Models\User;

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
        return view('auth.verificar-2fa');
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
            Auth::guard('web')->logout();//cierra sesion ya que no puede accerder
            // Si 2FA está habilitado, redireccionar al usuario a la página de verificación 2FA.
            return redirect()->route('2fact');
        }
        // Si 2FA no está habilitado, redireccionar al usuario a la página de inicio de la aplicación.
        return redirect(RouteServiceProvider::HOME);
    }
    private function generateGoogle2FA(User $user)
    {
        $google2fa = app(Google2FA::class);
        $google2faUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
        $secretKey = $google2fa->generateSecretKey();
        dd($secretKey);
        // Aquí puedes guardar el código QR y la clave secreta en la base de datos si es necesario.
        // Por ejemplo:
        // $user->update([
        //     'google2fa_qr' => $qrImage,
        //     'google2fa_secret' => $secretKey,
        // ]);

        // También puedes pasar el código QR y la clave secreta a la vista usando variables de sesión si prefieres:
        // $request->session()->put('qrImage', $qrImage);
        // $request->session()->put('secretKey', $secretKey);

        // Luego en la vista, puedes acceder a las variables de sesión para mostrar el código QR y la clave secreta.
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
