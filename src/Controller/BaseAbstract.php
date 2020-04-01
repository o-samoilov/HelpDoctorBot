<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseAbstract extends AbstractController
{
    // ########################################

    protected function createErrorResponse(string $message, int $code = 0): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json([
            'status'  => 'error',
            'message' => $message,
            'code'    => $code,
        ]);
    }

    // ########################################
}
