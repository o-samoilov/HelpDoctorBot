<?php

declare(strict_types=1);

namespace App\Model\Pipe\Command;

class SetUserVar extends \App\Model\Pipe\BaseAbstract
{
    /** @var int */
    private $uid;

    /** @var string */
    private $varname;

    /** @var string */
    private $varvalue;

    // ########################################

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function setVarname(string $varname): self
    {
        $this->varname = $varname;

        return $this;
    }

    public function setVarvalue(string $varvalue): self
    {
        $this->varvalue = $varvalue;

        return $this;
    }

    // ########################################

    public function getUrl(): string
    {
        return $this->getHost() . "user/{$this->uid}?apikey={$this->getApiKey()}&{$this->varname}={$this->varvalue}";
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
