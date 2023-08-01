<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
class PostsRedditController extends Controller
{
   public function store(Request $request){
        $accessToken = Session::get('reddit_access_token');//trae al token se la sesion
        if (!$accessToken) {
            return redirect()->route('reddit.auth'); // Redirige  a la pagina de autenticación de Reddit si el token no está presente
        }
        $validator = Validator::make($request->all(), [//se valida el form
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subreddit' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {//returna el error
            return back()->withErrors($validator)->withInput();
        }

        $title = $request->input('title');
        $content = $request->input('content');
        $subreddit = $request->input('subreddit');

        $client = new Client([
            'base_uri' => 'https://oauth.reddit.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'User-Agent' => 'TrodoTC', // Cambia esto al nombre de tu aplicación
            ],
        ]);

        // Realiza la publicación en Reddit utilizando el token de acceso
        $response = $client->post('api/submit', [
            'form_params' => [
                'kind' => 'self',
                'sr' => $request->input('subreddit'),
                'title' => $request->input('title'),
                'text' => $request->input('content'),
            ],
        ]);
        //dd($response);
        // Verifica la respuesta de Reddit
        if ($response->getStatusCode() == 200) {
            // Publicación exitosa, redirige a la vista de publicaciones
            return redirect()->route('publicaciones.index')->with('success', 'Publicación exitosa en Reddit.');
        } else {
            // Error en la publicación, redirige con un mensaje de error
            return back()->with('error', 'Error al publicar en Reddit.');
        }

   }
}
