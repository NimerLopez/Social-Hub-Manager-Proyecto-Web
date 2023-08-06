<?php

namespace App\Http\Controllers;

use App\Tools\SaveHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class LinkedinPostController extends Controller
{
    public function index(){
        return view('publicaciones.linkedin');
    }
    public function postOnLinkedin(Request $request){
        $accessToken = Session::get('linkedin_access_token');//trae al token se la sesion
        $userId = Session::get('linkedin_user_id');//trae id de usuario
        //dd($accessToken , $userId);
        if (!$accessToken && !$userId) {
            return redirect()->route('linkedin.auth');
        }

        $validator = Validator::make($request->all(), [
            'linkedin_text' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {//returna el error
            return back()->withErrors($validator)->withInput();
        }
        $shareData = [
            "owner" => "urn:li:person:{$userId}",
            "text" => [
                "text" => $request->input('linkedin_text')
            ]
        ];
        try{
            $responsemessage = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
                'Content-Type' => 'application/json',
            ])->post('https://api.linkedin.com/v2/shares', $shareData);
            
            if ($responsemessage->getStatusCode() == 201) {
                $saveHistory = new SaveHistory();          
                $saveHistory->save('status','Publicacion Exitosa: code en Linkendin-> ' . $responsemessage->getStatusCode());
                return redirect()->route('publicaciones.linkedin')->with('success', 'Publicación exitosa en Linkendin.');  
            }else{       
                $saveHistory = new SaveHistory();          
                $saveHistory->save('status','Errror de credenciales: code en Linkendin-> ' . $responsemessage->getStatusCode());
                return back()->with('error-status', 'Ocurrió un error en la aplicación Renueve el token: ' . $responsemessage->getStatusCode());
            }
                  
        }catch(\Exception $e){
            $errorMessage = $e->getMessage();
            $saveHistory = new SaveHistory();
            $saveHistory->save('error', 'Error en la aplicación: ' . $errorMessage);
            return back()->with('error-status', 'Ocurrió un error en la aplicación: ' . $errorMessage);
        }
    

    }

}

