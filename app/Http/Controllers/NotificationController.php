<?php

namespace App\Http\Controllers;




class NotificationController extends Controller
{
    public function send($token,$title,$body)
{
    $SERVER_API_KEY = 'AAAAIhkfa9I:APA91bHOX9Eu1NmUW0Lftue0q30uyEMl7Xni_P8Lubg6UlKHuuhKxvxq9FTRFivrttTMTv5YuszozV-YaOYVW3nBf84jgv1bDwLsklIJgrLpfCEx38bo6kEcyj96dvYOP1FUTesrCg59';
  
        // payload data, it will vary according to requirement
        $data = [
            "to" =>$token,
            "notification" => [
                'title' => $title,
                'body' => $body,
               
            ]
        ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
      
        curl_close($ch);
      
        return $response;


} }