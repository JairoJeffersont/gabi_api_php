<?php

namespace GabineteDigital\Middleware;

class GetJson {

    function getJson($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return ['error' => $error];
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        } else {
            return ['error' => json_last_error_msg()];
        }
    }
    
}
