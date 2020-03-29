<?php

declare(strict_types=1);

namespace App\Event;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceListener
{
    public function onKernelRequest(RequestEvent $event)
    {
        if (!file_exists(__DIR__ . '/../../maintenanceFlag')) {
            return;
        }

        $event->setResponse(
            new Response(
                'Site is in maintenance mode.',
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );
        $event->stopPropagation();
    }
}
