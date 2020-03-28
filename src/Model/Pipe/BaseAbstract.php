<?php

declare(strict_types=1);

namespace App\Model\Pipe;

abstract class BaseAbstract
{
    protected const REQUEST_TYPE_GET  = 'GET';
    protected const REQUEST_TYPE_POST = 'POST';

    /** @var \App\Model\Config\Pipe */
    private $config;

    /** @var \App\Model\Curl\Processor */
    private $curlProcessor;

    // ########################################

    public function __construct(
        \App\Model\Config\Pipe $config,
        \App\Model\Curl\Processor $curlProcessor
    ) {
        $this->config        = $config;
        $this->curlProcessor = $curlProcessor;
    }

    // ########################################

    public function process(): void
    {
        $response = $this->curlProcessor->processPost($this->getUrl(), $this->getData());

        if ($response['status'] !== 'ok') {
            //todo log
        }
    }

    // ########################################

    protected function getApiKey(): string
    {
        return $this->config->getApiKey();
    }

    protected function getHost(): string
    {
        return $this->config->getHost();
    }

    // ########################################

    abstract public function getUrl(): string;

    abstract public function getData(): array;

    abstract public function getRequestType(): string;

    // ########################################
}