<?php

namespace App\Http\Controllers;




class NotificationController extends Controller
{
    public function send($tokens,$msg,$notifyData)
    {


    /**
     * Write code on Method
     *
     * @return response()
     */

        try {
           // $tokens[] = '90|1Aq68H49MBIPkgM7fpF9eyQEiqO5GuUhfpRoGSAK';
                         

            $serverKey = 'AAAAIhkfa9I:APA91bHOX9Eu1NmUW0Lftue0q30uyEMl7Xni_P8Lubg6UlKHuuhKxvxq9FTRFivrttTMTv5YuszozV-YaOYVW3nBf84jgv1bDwLsklIJgrLpfCEx38bo6kEcyj96dvYOP1FUTesrCg59';
           
            $registrationIds = $tokens;
            if (count($tokens) > 1) {
                $fields = array(
                    'registration_ids' => $registrationIds, //  for  multiple users
                    'notification'  => $notifyData,
                    'data' => $msg,
                    'priority' => 'high'
                );
            } else {
                $fields = array(
                    'to' => $registrationIds[0], //  for  only one users
                    'notification'  => $notifyData,
                    'data' => $msg,
                    'priority' => 'high'
                );
            }
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key=' . $serverKey;
            $URL = 'https://fcm.googleapis.com/fcm/send';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
        } catch (\Exception $e) {
        }


        return $result;

      
    }
} 