<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LinkedinController extends Controller
{
    public function linkedinToReddit(){
        
        $client_id = env('LINKEDIN_CLIENT_ID');
        $redirect_uri = env('LINKEDIN_REDIRECT_URI');
        $state = csrf_token(); // Puedes usar esto para proteger contra ataques CSRF      
        // Construye la URL de autorización de LinkedIn
        $url = "https://www.linkedin.com/oauth/v2/authorization?";
        $url .= "response_type=code";
        $url .= "&client_id={$client_id}";
        $url .= "&redirect_uri={$redirect_uri}";
        $url .= "&state={$state}";
        $url .= "&scope=r_liteprofile%20r_emailaddress%20w_member_social"; // Los permisos que deseas obtener

        return redirect($url);
    }
    public function handleLinkedinCallback(Request $request){
        //$state = $request->query('state');
        $access_token = "";
        $code = "";
        //GENERA EL TOKEN DE ACCESO
        $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
            'grant_type' => 'authorization_code',
            'code' => $request->code,
            'redirect_uri' => env('LINKEDIN_REDIRECT_URI'),
            'client_id' => env('LINKEDIN_CLIENT_ID'),
            'client_secret' => env('LINKEDIN_CLIENT_SECRET')
        ]);
        $access_token = $response["access_token"];
        //genera el id de usuario
        $user_id = Http::withToken($access_token)->get('https://api.linkedin.com/v2/me');
        $code = $user_id["id"];

        //envio de mensajes 
        $shareData = [
            "owner" => "urn:li:person:{$code}",
            "text" => [
                "text" => "Hello World! This is my first Share on LinkedIn!"
            ]
        ];


        $responsemessage = Http::withHeaders([
            'Authorization' => "Bearer {$access_token}",
            'Content-Type' => 'application/json',
        ])->post('https://api.linkedin.com/v2/shares', $shareData);


        dd($responsemessage->status(), $responsemessage->json());      
        dd("Hola");
    }
}
