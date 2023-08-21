<?php

namespace App\Http\Controllers;

use App\Models\Twitter_access;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TwitterController extends Controller
{

    public function Oauth2()
    {
        $tw_access_Model = new Twitter_access();
        $tw_access = $tw_access_Model->getTwitterAccess(auth()->user()->id);
        if ($tw_access->count() > 0) {
            return redirect('/connect')
                ->withErrors("Your are already connected to twitter");
        }
        $url = "https://twitter.com/i/oauth2/authorize?response_type=code&client_id=NG14YUVlanRKUHkyRnJTMDNpTGI6MTpjaQ&redirect_uri=http://socialhub.xyz/connectTwitter&scope=tweet.read%20tweet.write%20users.read%20follows.read%20offline.access&state=state&code_challenge=challenge&code_challenge_method=plain";
        return redirect($url);
    }

    public function connect(Request $request)
    {
        $response = Http::asForm()->post('https://api.twitter.com/2/oauth2/token', [
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'client_id' => 'NG14YUVlanRKUHkyRnJTMDNpTGI6MTpjaQ',
            'redirect_uri' => 'http://socialhub.xyz/connectTwitter',
            'code_verifier' => 'challenge'
        ]);

        if ($response->failed()) {
            return redirect('/connect')
                ->withErrors("Cannot connect to Twitter, try Again");
        }

        if ($response->successful()) {

            //atributos que se van a guardar en la base de datos

            $attributtes = ([
                'user_id' => auth()->user()->id,
                'tw_access_token' => $response["access_token"],
                'tw_refresh_token' => $response["refresh_token"]
            ]);

            try {
                Twitter_access::create($attributtes);
                return redirect('/home')
                    ->with("success", "Your connection was success");
            } catch (\Exception $e) {
                echo "Unknow Error";
            }
        }
    }

    public function refreshTwToken()
    {
        //obtner el id del usuario
        $_userId = auth()->user()->id;

        //instanciar un nuevo modelo 
        $Tw_access_Model = new Twitter_access();
        $tw_access = $Tw_access_Model->getTwitterAccess($_userId);

        if ($tw_access->count() != 0) {

            $response = Http::asForm()->post('https://api.twitter.com/2/oauth2/token', [
                'refresh_token' => $tw_access[0]->tw_refresh_token,
                'grant_type' => 'refresh_token',
                'client_id' => 'NG14YUVlanRKUHkyRnJTMDNpTGI6MTpjaQ',
            ]);

            $attributes = ([
                'tw_access_token' => $response["access_token"],
                'tw_refresh_token' => $response["refresh_token"]
            ]);

            //validar que el response haya sido correcto
            if ($response->successful()) {
                $Tw_access_Model->refreshTokens($attributes, $_userId);
            }
        }
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
