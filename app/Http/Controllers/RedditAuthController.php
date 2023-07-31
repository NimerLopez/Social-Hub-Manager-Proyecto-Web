<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class RedditAuthController extends Controller
{
    public function index()
    {   
        return view('publicaciones.index');
    }
    public function redirectToReddit()
    {
        $state = bin2hex(random_bytes(16)); // Genera un estado aleatorio para evitar ataques CSRF.
        session(['reddit_state' => $state]);
        $redditClientId = env('REDDIT_CLIENT_ID');
        $redirectUri = env('REDDIT_REDIRECT_URI');

        $query = http_build_query([
            'client_id' => $redditClientId,
            'response_type' => 'code',
            'state' => $state,
            'redirect_uri' => $redirectUri,
            'duration' => 'permanent', // Permite acceso permanente a la cuenta del usuario.
            'scope' => 'identity submit', // Los permisos requeridos (pueden ajustarse según tus necesidades).
        ]);

        return redirect("https://www.reddit.com/api/v1/authorize?$query");
    }

    public function handleRedditCallback(Request $request)
    {
        $state = $request->query('state');
        if ($state !== session('reddit_state')) {
            return redirect()->route('reddit.auth')->with('error', 'Invalid state. Possible CSRF attack.');
        }
        $redditClientId = env('REDDIT_CLIENT_ID');
        $redditClientSecret = env('REDDIT_CLIENT_SECRET');
        $redirectUri = env('REDDIT_REDIRECT_URI');
        $response = Http::asForm()->withBasicAuth($redditClientId, $redditClientSecret)->post('https://www.reddit.com/api/v1/access_token', [
            'grant_type' => 'authorization_code',
            'code' => $request->query('code'),
            'redirect_uri' => $redirectUri,
        ]);
        $data = $response->json();
        //dd($data);
        $accessToken = $data['access_token'];
        Session::put('reddit_access_token', $accessToken);
        return redirect()->route('publicaciones.index');
    }
}
