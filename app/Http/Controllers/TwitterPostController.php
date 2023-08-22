<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class TwitterPostController extends Controller
{
   
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
        $response = $this->requestSendPostTW($post_description);
        if ($response->status() != 201) {
            $tw_controller = new TwitterController();
            $tw_controller->refreshTwToken();
            $send = false;
        }
        return $send;
    }
}
