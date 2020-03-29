<?php

declare(strict_types=1);

namespace App\Model\Pipe;

abstract class BaseAbstract
{
    protected const REQUEST_TYPE_GET  = 'GET';
    protected const REQUEST_TYPE_POST = 'POST';

    private const PIPE_SUCCESS_RESPONSE = 'ok';

    /** @var \App\Model\Config\Pipe */
    private $config;

    /** @var \App\Model\Curl\Processor */
    private $curlProcessor;

    /** @var \App\Model\Pipe\Response\Factory */
    private $responseFactory;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    // ########################################

    public function __construct(
        \App\Model\Config\Pipe $config,
        \App\Model\Curl\Processor $curlProcessor,
        \App\Model\Pipe\Response\Factory $responseFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->config          = $config;
        $this->curlProcessor   = $curlProcessor;
        $this->responseFactory = $responseFactory;
        $this->logger          = $logger;
    }

    // ########################################

    public function process(): \App\Model\Pipe\Response
    {
        if ($this->getRequestType() === self::REQUEST_TYPE_POST) {
            [$httpCode, $responseData] = $this->curlProcessor->processPost($this->getUrl(), $this->getData());
        } else {
            [$httpCode, $responseData] = $this->curlProcessor->processGet($this->getUrl());
        }

        if ($httpCode !== 200) {
            $this->logger->error('Invalid http code.', [
                'http_code'     => $httpCode,
                'response_data' => $responseData,
                'url'           => $this->getUrl(),
                'method'        => $this->getRequestType(),
                'data'          => $this->getData(),
            ]);

            $errorMessage = $responseData['message'] ?? 'Pipe bot invalid response. Http code invalid.';

            return $this->responseFactory->createFail($errorMessage);
        }

        if (!isset($responseData['status']) || $responseData['status'] !== self::PIPE_SUCCESS_RESPONSE) {
            $this->logger->error('Pipe error response.', [
                'http_code'     => $httpCode,
                'response_data' => $responseData,
                'url'           => $this->getUrl(),
                'method'        => $this->getRequestType(),
                'data'          => $this->getData(),
            ]);

            $errorMessage = $responseData['message'] ?? 'Pipe bot invalid response.';

            return $this->responseFactory->createFail($errorMessage);
        }

        return $this->responseFactory->createSuccess($responseData['data'] ?? []);
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
