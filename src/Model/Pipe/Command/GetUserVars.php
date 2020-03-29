<?php

declare(strict_types=1);

namespace App\Model\Pipe\Command;

class GetUserVars extends \App\Model\Pipe\BaseAbstract
{
    /** @var int */
    private $uid;

    // ########################################

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    // ########################################

    public function getUrl(): string
    {
        return $this->getHost() . "user/{$this->uid}/exp?apiley={$this->getApiKey()}";
    }

    public function getData(): array
    {
        return [];
    }

    public function getRequestType(): string
    {
        return self::REQUEST_TYPE_GET;
    }

    // ########################################
}
