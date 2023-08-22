<?php

namespace App\Http\Controllers;

use App\Models\Twitter_access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TwitterController extends Controller
{

    public function ConnectOautTwitter()
    {
        $state = bin2hex(random_bytes(16)); 
    
        $query = http_build_query([
            'response_type' => 'code',
            'client_id' => env('TWITTER_CLIENT_ID'),
            'redirect_uri' => env('TWITTER_REDIRECT_URI'),
            'scope' => 'tweet.read tweet.write users.read follows.read offline.access',
            'state' => $state,
            'code_challenge' => 'challenge',
            'code_challenge_method' => 'plain'
        ]);
        
        $url = "https://twitter.com/i/oauth2/authorize?" . $query;
    
        return redirect($url);
    }

    public function TwitterCallback(Request $request)
    {
        $response = Http::asForm()->post('https://api.twitter.com/2/oauth2/token', [
        'code' => $request->code,
        'grant_type' => 'authorization_code',
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect_uri' => env('TWITTER_REDIRECT_URI'),
        'code_verifier' => 'challenge' 
    ]);

    dd($response);

    if ($response->failed()) {
        return redirect('/connect')
            ->withErrors("Cannot connect to Twitter, try again");
    }

    // Process the response and obtain access token
    $access_token = $response['access_token'];
    }

   

    //realizar el request para mandar el post
    public function requestSendPostTW($post_description)
    {
        $tw_access_Model = new Twitter_access();
        $tw_access = $tw_access_Model->getTwitterAccess(auth()->user()->id);

        $response = Http::withHeaders([
            'Content_type' => 'application/json',
            'Authorization' => "Bearer {$tw_access[0]->tw_access_token}"
        ])->post('https://api.twitter.com/2/tweets', [
            'text' => $post_description
        ]);

        return $response;
    }

    public function twPost($post_description)
    {
        $send = true;
        //obtener el id del usuario
        $response = $this->requestSendPostTW($post_description);
        //validar si el request fue valido
        if ($response->status() != 201) {
            $tw_controller = new TwitterController();
            $tw_controller->refreshTwToken();
            $send = false;
        }
        return $send;
    }
}
