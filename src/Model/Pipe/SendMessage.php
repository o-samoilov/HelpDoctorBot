<?php

declare(strict_types=1);

namespace App\Model\Pipe;

class SendMessage extends BaseAbstract
{
    /** @var int */
    private $uid;

    /** @var string */
    private $message;

    // ########################################

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    // ########################################

    public function getUrl(): string
    {
        return $this->getHost() . "/user/{$this->uid}/messages/";
    }

    public function getData(): array
    {
        return [
            'apikey'  => $this->getApiKey(),
            'message' => $this->message,
        ];
    }

    public function getRequestType(): string
    {
        return self::REQUEST_TYPE_POST;
    }

    // ########################################
}
