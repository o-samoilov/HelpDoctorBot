<?php

declare(strict_types=1);

namespace App\Model\Pipe\Response;

class Factory
{
    // ########################################

    public function createSuccess(array $data): \App\Model\Pipe\Response
    {
        return new \App\Model\Pipe\Response(true, $data, null);
    }


    public function createFail(string $errorMessage): \App\Model\Pipe\Response
    {
        return new \App\Model\Pipe\Response(false, null, $errorMessage);
    }

    // ########################################
}
