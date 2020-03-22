<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    // ########################################

    /**
     * @Route("/user/create", methods={"POST"})
     */
    public function create(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $request = Request::createFromGlobals();
        $data    = json_decode($request->getContent(), true);

        return $this->json([
            'status' => 'ok',
            'data'   => [],
        ]);
    }

    private function createErrorResponse(): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json(['status' => 'error']);
    }

    // ########################################
}
