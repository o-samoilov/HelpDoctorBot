<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $botKey = '1081850027:AAFJkaQ9C2fpI1JA7cRghxaY-uSPJQsXqho';

        $client         = new \Http\Adapter\Guzzle6\Client();
        $requestFactory = new \Http\Factory\Guzzle\RequestFactory();
        $streamFactory  = new \Http\Factory\Guzzle\StreamFactory();

        $apiClient = new \TgBotApi\BotApiBase\ApiClient($requestFactory, $streamFactory, $client);
        $bot       = new \TgBotApi\BotApiBase\BotApi($botKey, $apiClient, new \TgBotApi\BotApiBase\BotApiNormalizer());

        $updates = $bot->getUpdates(\TgBotApi\BotApiBase\Method\GetUpdatesMethod::create());

        return $this->json($updates);
    }
}
