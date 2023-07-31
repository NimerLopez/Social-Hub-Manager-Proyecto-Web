<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

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
            Auth::login($user);    
            return redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Código 2FA verificado con éxito. Bienvenido.');
        }

        return redirect()->back()->withErrors(['2fa_token' => 'El código 2FA ingresado es incorrecto.']);
    }

    private function checkGoogleAuthenticatorCode($secret, $code) {
        $g = new GoogleAuthenticator();
        return $g->checkCode($secret, $code);
    }


    public function updateGoogle2faEnabledStatus(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'google2fa_enabled' => 'required|boolean',
        ]);

        $user->google2fa_enabled = $data['google2fa_enabled'];
        //dd($user);
        if(!$user->google2fa_secret){
            $this-> generateGoogle2FA($user);
        }
        $user->save();

        $message = $data['google2fa_enabled']//mensajes
            ? '2FA ha sido habilitado correctamente.'//si esta habilitado
            : '2FA ha sido deshabilitado correctamente.';//si no esta habilitado

        return redirect()->back()->with('status', $message);
    }
    public function changeCredentials2FA(){
        $user = Auth::user();
        $this-> generateGoogle2FA($user);
        return redirect()->back()->with('status-change', 'Se Cambiaron las Credenciales 2FA, Debe Escanear el codigo');
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
}
