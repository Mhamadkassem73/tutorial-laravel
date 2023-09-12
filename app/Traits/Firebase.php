<?php


namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait Firebase
{

    public  function firebaseNotification($fcmNotification){

         try {
            $fcmUrl =config('firebase.fcm_url');
    
     $apiKey=config('firebase.fcm_api_key');

        $http=Http::withHeaders([
            'Authorization'=>'Bearer '.$apiKey,
            'Content-Type'=>'application/json; UTF-8'
        ])  ->post($fcmUrl,$fcmNotification);

        return  $http->json();
         } catch (\Throwable $th) {
           return 0;
         }
    }
}