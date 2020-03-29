<?php

declare(strict_types=1);

namespace App\Model\Pipe;

class Response
{
    /** @var bool */
    private $isSuccess;

    /** @var array|null */
    private $data;

    /** @var string|null */
    private $errorMessage;

    // ########################################

    public function __construct(bool $isSuccess, ?array $data, ?string $errorMessage)
    {
        $this->isSuccess    = $isSuccess;
        $this->data         = $data;
        $this->errorMessage = $errorMessage;
    }

    // ########################################

    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    // ########################################
}
