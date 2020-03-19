<?php

declare(strict_types=1);

namespace App\Model\Telegram\Command;

abstract class BaseAbstract
{
    // ########################################

    abstract public function validate(): bool;

    abstract public function process(): void;

    // ########################################

    /*public function createSuccessResponse(): Response
    {
        return $this->responseFactory->create();
    }

    public function createFailedResponse(string $message): Response
    {
        return $this->responseFactory->create(false, $message);
    }*/

    // ########################################
}
