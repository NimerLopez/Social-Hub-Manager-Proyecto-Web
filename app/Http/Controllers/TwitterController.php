<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
//use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterController extends Controller
{
   public function ConnectOautTwitter(){
    $user = Socialite::driver('twitter')->user();
    dd($user);    
    // $user = Socialite::driver('facebook')->user();
    /// Save the user's Twitter ID in the database.
    // dd($user);
    // $this->user->twitter_id = $user->id;
    // $this->user->save();
    return redirect()->back()->with('status-twitter', 'Se Conecto a twitter');
   }
}
 