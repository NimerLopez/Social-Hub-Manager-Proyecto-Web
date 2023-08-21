<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
//use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterController extends Controller
{
   public function ConnectOautTwitter(){    
    return Socialite::driver('twitter')->redirect();
   }
   public function TwitterCallback(Request $request){
      try {
      $user = Socialite::driver('twitter')->user();
      $request->session()->put('twitter_access_token', $user->token);
      $request->session()->put('twitter_access_token_secret', $user->tokenSecret);    
     } catch (\Exception $e) {
         return redirect('/dashboard')->with('error', 'Ha ocurrido un error al autenticar con Twitter.');
     }
     return redirect()->route('publicaciones.twitter')->with('success', 'Autenticado correctamente con Twitter.');
   }
}
 