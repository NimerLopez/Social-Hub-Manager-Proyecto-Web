<?php

namespace App\Tools;
use App\Models\LinkedinSessions;
use App\Models\Postqueue;
use App\Models\RedditSessions;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Date;
use Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;



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
            //VALIDAR RESPONSE Y GUARDAR EN HISTORIAL
            if ($response->getStatusCode()==201) {
                $saveHistory = new SaveHistory();
                $saveHistory->saveCron('status','Publicacion Exitosa Reddit: code->' . $response->getStatusCode(),$queueData->id_usuario);           
            
                $deliteQueue= Postqueue::find($queueData->id);//busca la c
                $deliteQueue->delete();
            }else{
                $saveHistory = new SaveHistory();
                $saveHistory->saveCron('error', 'Error en la aplicación Reddit: ',$queueData->id_usuario);
            }
            
                   
            return $response;
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            $saveHistory = new SaveHistory();
            $saveHistory->saveCron('error', 'Error en la aplicación Reddit: ' . $errorMessage,$queueData->id_usuario);
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
                $saveHistory = new SaveHistory();
                $saveHistory->saveCron('status','Publicacion Exitosa Linkedin: code->' . $responsemessage->getStatusCode(),$queueData->id_usuario);           
                
                $deliteQueue= Postqueue::find($queueData->id);
                $deliteQueue->delete();
            }else{            
                $saveHistory = new SaveHistory();
                $saveHistory->saveCron('error', 'Error en la aplicación Linkedin: ',$queueData->id_usuario);
            }     
            return $responsemessage->getStatusCode();           
        } catch (\Throwable $th) {
            $errorMessage = $th->getMessage();
            $saveHistory = new SaveHistory();
            $saveHistory->saveCron('error', 'Error en la aplicación Linkedin: ' . $errorMessage,$queueData->id_usuario);
            return $th;
        }
       
    }
    

   
}
