<?php

namespace App\Http\Controllers;




class NotificationController extends Controller
{
    public function send()
{
    $SERVER_API_KEY = 'AAAAIhkfa9I:APA91bHOX9Eu1NmUW0Lftue0q30uyEMl7Xni_P8Lubg6UlKHuuhKxvxq9FTRFivrttTMTv5YuszozV-YaOYVW3nBf84jgv1bDwLsklIJgrLpfCEx38bo6kEcyj96dvYOP1FUTesrCg59';
  
        // payload data, it will vary according to requirement
        $data = [
            "to" =>'cCHAqx6MS1yReG3mRkjKCc:APA91bGF_kZpUd5ToeeWnW4rbF4AYHHYN7cqbzMcoytHfg0gCI629mdSygbyYs7O-2XXtZYNmLNfLa5wDWXJsDIg-yzTSHZ0n-8RBLulXSDX2H1XFPeiXSbXJ3kd5Zgghno46IC3VAIf',
            "notification"     =>  [
                'title'     => 'Daniaaa',
                'body' => "heyyyyy",]
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