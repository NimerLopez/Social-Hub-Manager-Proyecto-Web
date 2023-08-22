<?php

namespace App\Tools;

use App\tools\SaveHistory;
use App\Models\LinkedinSessions;
use App\Models\Postqueue;
use App\Models\RedditSessions;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Date;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Storage;


class PostQueueSend
{
    public function ValidatePostOldestSocialNetwork($user_id)
    {
            $queueIns = new Postqueue;
            $queueData = $queueIns->getOldestPostsByUser($user_id);  
            //dd($queueData);
            if ($queueData ) {
                if ($queueData->redsocial=="Reddit") {
                    $x=$this->SendToQueueToReddit($queueData);
                   return $x;
                }
                if ($queueData->redsocial=="Linkedin") {
                    $x=$this->SendToQueueToLinkedIn($queueData);
                   return $x;
                }
            }                      
            return 0;
    }
    public function ValidatePostDateSocialNetwork($queue){
        if ($queue ) {
            if ($queue->redsocial=="Reddit") {
                $x=$this->SendToQueueToReddit($queue);
               return $x;
            }
            if ($queue->redsocial=="Linkedin") {
                $x=$this->SendToQueueToLinkedIn($queue);
               return $x;
            }
        }
    }
    private function SendToQueueToReddit( $queueData){
        $tokenIns= new RedditSessions;
        $accesToken=  $tokenIns->getRedditAccess($queueData->id_usuario);
        
        try {
            $client = new Client([//declaracion de estructura del request
                'base_uri' => 'https://oauth.reddit.com/',
                'headers' => [
                    'Authorization' => 'Bearer ' . $accesToken,
                    'User-Agent' => 'TrodoTC',
                ],
            ]);
            $response = $client->post('api/submit', [
                'form_params' => [
                    'kind' => 'self',
                    'sr' => $queueData->group,
                    'title' => $queueData->title,
                    'text' => $queueData->message,
                ],
            ]);
            $deliteQueue= Postqueue::find($queueData->id);//busca la c
            $deliteQueue->delete();
            //VALIDAR RESPONSE Y GUARDAR EN HISTORIAL
            $saveHistory = new SaveHistory();
            $saveHistory->save('status','Publicacion Exitosa: code-> ' . $response->getStatusCode());                   
            return $response;
        } catch (\Throwable $th) {
            $errorMessage = $e->getMessage();
            $saveHistory = new SaveHistory();
            $saveHistory->save('error', 'Error en la aplicación: ' . $errorMessage);
            return $th;
        }

        
    }
    private function SendToQueueToLinkedIn($queueData){
        $tokenIns= new LinkedinSessions;
        $KeysAcces=  $tokenIns->getLinkedAccess($queueData->id_usuario);
        try {
            $shareData = [
                "owner" => "urn:li:person:{$KeysAcces->linkedin_user_id}",
                "text" => [
                    "text" => $queueData->message
                ]
            ];
            $responsemessage = Http::withHeaders([
                'Authorization' => "Bearer {$KeysAcces->linkedin_access_token}",
                'Content-Type' => 'application/json',
            ])->post('https://api.linkedin.com/v2/shares', $shareData);
            if ($responsemessage->getStatusCode() == 201) {
                $deliteQueue= Postqueue::find($queueData->id);//busca la c
                $deliteQueue->delete();
            }else{
                //guardar historial del error
            }     
            return $responsemessage->getStatusCode();           
        } catch (\Throwable $th) {
            return $th;
        }
       
    }
    
   
}
