<?php

class LogiController extends ApiController
{
    public function secretKey() {
        $user = Auth::getUser();
        $ip =  '127.0.0.1';
        $url = 'http://royaltysnapshot.com:8080/RoyaltyExchange/rdTemplate/rdGetSecureKey.aspx?Username='.$user->email.'&ClientBrowserAddress='.$ip;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $result = curl_exec($ch);
        $secretKey ='';
        $http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($http_status_code == 200)
        {
            $secretKey = $result;
        }
        curl_close($ch);
        return Response::json(array(
            'secretKey'  => $secretKey ), 200, [], JSON_NUMERIC_CHECK);

    }
} 