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
            Auth::guard('web')->logout(); //cierra sesion ya que no puede accerder
            session(['user_id' => $user->id]);//crea una sesion con el user-id      
            return redirect()->route('2fact');//redicreciona al 2fac
        }
        // Si 2FA no está habilitado, redireccionar al usuario a la página de inicio de la aplicación.
        return redirect(RouteServiceProvider::HOME);
    }

    //php artisan make:controller GoogleAuthenticatorController
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