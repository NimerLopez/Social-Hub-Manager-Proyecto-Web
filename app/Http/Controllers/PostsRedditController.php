<?php

namespace App\Http\Controllers;

use App\tools\SaveHistory;
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
        $client = new Client([//declaracion de estructura del request
            'base_uri' => 'https://oauth.reddit.com/',
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'User-Agent' => 'TrodoTC',
            ],
        ]);

        // Realiza la publicación en Reddit
        try{
        $response = $client->post('api/submit', [
            'form_params' => [
                'kind' => 'self',
                'sr' => $request->input('subreddit'),
                'title' => $request->input('title'),
                'text' => $request->input('content'),
            ],
        ]);
        //dd($response);   
        // Publicación exitosa, redirige a la vista de publicaciones
        $saveHistory = new SaveHistory();
        $saveHistory->save('status','Publicacion Exitosa: code-> ' . $response->getStatusCode());
        return redirect()->route('publicaciones.index')->with('success', 'Publicación exitosa en Reddit.');      
    }catch(\Exception $e){
        $errorMessage = $e->getMessage();
        $saveHistory = new SaveHistory();
        $saveHistory->save('error', 'Error en la aplicación: ' . $errorMessage);
        return back()->with('error-status', 'Ocurrió un error en la aplicación: ' . $errorMessage);
    }//fin catch
   }//fin store
}
