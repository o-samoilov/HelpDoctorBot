<?php

declare(strict_types=1);

namespace App\Model\Telegram\Command;

class Start extends BaseAbstract
{
    /** @var \TgBotApi\BotApiBase\ApiClient */
    private $apiClient;

    // ########################################

    public function __construct(\TgBotApi\BotApiBase\ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    // ########################################

    public function validate(): bool
    {
        return true;
    }

    // ########################################

    public function process(): void
    {
        // TODO: Implement process() method.
    }

    // ########################################
}
