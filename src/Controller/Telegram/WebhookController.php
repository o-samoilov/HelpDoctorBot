<?php

declare(strict_types=1);

namespace App\Controller\Telegram;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class WebhookController extends AbstractController
{
    // ########################################

    /**
     * Endpoint Telegram webhook
     *
     * @Route("/telegram/webhook/{accessKey}", name="telegram_controller")
     *
     * @param string                           $accessKey
     * @param \App\Model\Telegram\Auth\Checker $authChecker
     * @param \Psr\Log\LoggerInterface         $logger
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(
        string $accessKey,
        \App\Model\Telegram\Auth\Checker $authChecker,
        \Psr\Log\LoggerInterface $logger
    ) {
        $request  = Request::createFromGlobals();
        $response = new Response();

        if (!$authChecker->isValidAccessKey($accessKey)) {
            $logger->alert('Invalid access key.', [
                '_SERVER'    => $_SERVER,
                '_POST'      => $_POST,
                'access_key' => $accessKey,
                'input'      => $request->getContent(),
            ]);

            $response->setContent(json_encode(['message' => 'Invalid access key.']));
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
        }

        $fetcher = new \TgBotApi\BotApiBase\WebhookFetcher(new \App\Model\Telegram\BotApiNormalizer());
        $update = $fetcher->fetch($request->getContent());

        //$a = $update->message->chat;

        return $response;
    }
    // ########################################
}
