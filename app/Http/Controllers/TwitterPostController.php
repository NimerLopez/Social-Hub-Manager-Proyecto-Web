<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class TwitterPostController extends Controller
{
    public function index(){
        return view('publicaciones.twitter');
    }
    public function postToTwitter(Request $request)
    {
        // Obtener el token de acceso y el token secreto almacenados en la sesión durante la autenticación.
        $accessToken = $request->session()->get('twitter_access_token');
        //$accessTokenSecret = $request->session()->get('twitter_access_token_secret');

        $tweet = $request->input('tweet');

        // Verifica que la variable $tweet no esté vacía antes de realizar el POST

        $url = 'https://api.twitter.com/2/tweets';

        $client = new Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' .  $accessToken, // Reemplaza 'TU_ACCESS_TOKEN' con tu token de acceso OAuth
                ],
                'json' => [
                    'text' => $tweet,
                ],
            ]);
            dd($response);
            // Aquí puedes agregar el código para manejar la respuesta de la API de Twitter, por ejemplo, guardar el ID del tweet creado, mostrar un mensaje de éxito, etc.
        } catch (Exception $e) {
            
        }
    }
}
