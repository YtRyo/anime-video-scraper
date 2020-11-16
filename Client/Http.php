<?php

namespace Client;

class Http
{
    public function get($url, array $data = null, $headers = null)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function post($url, array $data = null, $headers = null)
    {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
