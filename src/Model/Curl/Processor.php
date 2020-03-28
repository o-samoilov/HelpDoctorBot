<?php

declare(strict_types=1);

namespace App\Model\Curl;

class Processor
{
    // ########################################

    public function processPost(string $url, array $data): array
    {
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($handle);

        if ($response === false) {
            //todo exception
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            //error_log("Curl returned error $errno: $error\n");

            curl_close($handle);

            return false;
        }

        $httpCode = (int)curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($httpCode !== 200) {
            //todo log
        }

        return (array)json_decode($response, true);
    }

    // ########################################
}
