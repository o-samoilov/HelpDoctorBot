<?php

declare(strict_types=1);

namespace App\Model\Telegram;

class CommandResolver
{
    /** @var \App\Repository\UserRepository */
    private $userRepository;

    public function __construct(\App\Repository\UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // ########################################

    public function resolve(\TgBotApi\BotApiBase\Type\UpdateType $update): \App\Model\Telegram\Command\BaseAbstract
    {
        $from = $update->message->from;

        //$this->userRepository->find()
    }

    // ########################################
}
