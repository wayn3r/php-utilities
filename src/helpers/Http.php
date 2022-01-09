<?php

namespace Helpers;

final class Http {
    public static function get(string $url, array $params = []): array {
        if ($params) {
            $url .= '?';
            foreach ($params as $param => $value) {
                $url .= $param . '=' . $value . '&';
            }
            $url = rtrim($url, '&');
        }
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ]
        );

        return self::processResponse($curl);
    }
    public static function post(string $url, array $data): array {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ));
       return self::processResponse($curl);
    }

    private static function processResponse($curl):array {
        $response = curl_exec($curl);
        $respInfo = curl_getinfo($curl);
        $response = [
            'status' => $respInfo["http_code"],
            'response' => json_decode($response),
            'error' => curl_error($curl)
        ];
        curl_close($curl);
        return $response;
    }
}
