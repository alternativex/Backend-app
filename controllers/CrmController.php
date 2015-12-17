<?php

class CrmController extends ApiController
{
    public function token()
    {
        $headers = array(
            'X_USERNAME: apiwp',
            'X_PASSWORD: Rr416jJHGjhmee6p4S43',
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL =>
                'https://www.royaltysnapshot.com/crm/index.php/api/token/WpGuruUser/10',//10 = alin wp guru user
            CURLOPT_USERAGENT => 'Dashboard Request',
            CURLOPT_HTTPGET=>true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => $headers
        ));
        // Send the request & save response to $resp
        if(!$resp=curl_exec($curl)){
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }
        curl_close($curl);
        return json_decode($resp)->token;
    }
} 