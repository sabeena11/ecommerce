<?php

namespace App\Helper;

use App\Models\User;
use App\Models\SmsLog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class HttpClient
{
    public static function send_sms($user, $message)
    {
        $client = new Client();
        $url = 'https://send.api.mailtrap.io/api/send'; 

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $body = [
            'auth_token' => config('services.sms.api_key'), 
            'to' => $user->mobile,
            'text' => $message,
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'json' => $body,
            ]);

            $statusCode = $response->getStatusCode();

            
            

            return $statusCode;
        } catch (RequestException $e) {
            // Log the error or handle it as necessary
            

            return $e->getCode();
        }
    }
}
