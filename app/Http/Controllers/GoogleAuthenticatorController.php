<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use App\Providers\RouteServiceProvider;

class GoogleAuthenticatorController extends Controller
{
    public function aut2fac()
    {
        $userId = session('user_id');
        $user = User::findOrFail($userId);
        return view('auth.verificar-2fa', compact('user'));
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
        //dd($this->checkGoogleAuthenticatorCode($user->google2fa_secret, $code));
        if ($this->checkGoogleAuthenticatorCode($user->google2fa_secret, $code)) {
            return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Código 2FA verificado con éxito. Bienvenido.');
        }

        return redirect()->back()->withErrors(['2fa_token' => 'El código 2FA ingresado es incorrecto.']);
    }

    private function checkGoogleAuthenticatorCode($secret, $code) {
        $g = new GoogleAuthenticator();
        return $g->checkCode($secret, $code);
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

    public function updateGoogle2faEnabledStatus(Request $request, $id)
    {
        // Encuentra el post en la base de datos
        $user = User::find($id);       
        // Actualiza el estado "google2fa_enabled" con el valor recibido en la solicitud
        $user->google2fa_enabled = $request->input('google2fa_enabled');
        $user->save();
        // Retorna un mensaje de éxito
        $message = 'Estado de google2fa_enabled actualizado correctamente';
        return $message;
    }
    
}
