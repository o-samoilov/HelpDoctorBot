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
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            $info  = curl_getinfo($handle);

            curl_close($handle);

            throw new Exception\UnableProcess('Unable to process request.', [
                'curl_errno' => $errno,
                'curl_error' => $error,
                'curl_info'  => $info,
            ]);
        }

        $httpCode = (int)curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return [
            $httpCode,
            (array)json_decode($response, true),
        ];
    }

    // ########################################
}
