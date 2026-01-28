<?php

namespace App\Services;

class BkashTokenService
{
    public function getPaymentToken()
    {
        $appKey = config('bkash.app_key');
        $appSecret = config('bkash.app_secret');
        $baseUrl = config('bkash.base_url');
        $username = config('bkash.username');
        $password = config('bkash.password');

        $postToken = [
            'app_key' => $appKey,
            'app_secret' => $appSecret
        ];

        $url = curl_init("$baseUrl/v1.2.0-beta/tokenized/checkout/token/grant");
        $postToken = json_encode($postToken);
        $header = array(
            'Content-Type: application/json',
            'Accept: application/json',
            "password: $password",
            "username: $username"
        );

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $postToken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        $resultData = curl_exec($url);

        if ($resultData === false) {
            $error = curl_error($url);
            curl_close($url);
            return ['error' => $error];
        }

        curl_close($url);

        $response = json_decode($resultData, true);



        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['error' => 'Invalid JSON response from bKash API'];
        }

        if (isset($response['id_token'])) {
            session()->put('bkash_token', $response['id_token']);
            return [
                'success' => true,
                'id_token' => $response['id_token'],
        ];
        } else {
            return ['error' => 'Missing id_token in the bKash API response'];
        }
    }
}
