<?php

namespace App\Http\Controllers;

use App\Models\LinkedinSessions;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LinkedinController extends Controller
{
    public function linkedinToReddit()
    {       
        $state = bin2hex(random_bytes(16));//Genera un estado aleatorio para evitar ataques CSRF.
        session(['linkedin_state' => $state]);
        $linkedinClientId = env('LINKEDIN_CLIENT_ID');
        $redirectUri = env('LINKEDIN_REDIRECT_URI'); 
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => $linkedinClientId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'scope' => 'r_liteprofile r_emailaddress w_member_social',
            'duration' => 'permanent',
        ]); 
        session(['linkedin_state' => $state]);  
        return redirect("https://www.linkedin.com/oauth/v2/authorization?$query");
    }
    public function handleLinkedinCallback(Request $request){
    
        $state = $request->query('state');//validacion de estado
        if ($state !== session('linkedin_state')) {
            return redirect()->route('linkedin.auth')->with('error', 'Invalid state. Possible CSRF attack.');
        }

        $accesstoken = "";
        $code = "";
        //GENERA EL TOKEN DE ACCESO
        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET')
        ]);
        $accesstoken = $response["access_token"];

        //genera el id de usuario
        $user_id = Http::withToken($accesstoken)->get('https://api.linkedin.com/v2/me');
        $code = $user_id["id"];
        $attributes = ([
            'id_usuario' => auth()->user()->id,
            'linkedin_access_token' => $accesstoken,
            'linkedin_user_id' => $code
        ]);
        if ($user_id->successful()) {
            Session::put('linkedin_access_token', $accesstoken);//guarda en sesion
            Session::put('linkedin_user_id', $code);//guarda en sesion     
            LinkedinSessions::updateOrCreate(['id_usuario' => auth()->user()->id], $attributes);//guarda en la base de datos
            return redirect()->route('publicaciones.linkedin'); 
        } 
        //returnar error        
    }

}
