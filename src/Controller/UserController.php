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
     * @param \App\Entity\User\Factory       $userFactory
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(
        \App\Entity\User\Factory $userFactory,
        \App\Repository\UserRepository $userRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = json_decode($request->getContent(), true);

        if (!isset($data['uid']) || !is_int($data['uid'])) {
            return $this->createErrorResponse('Помилка вхідних данних.');
        }

        $user = $userRepository->findByUid(123);

        return $this->json([
            'status' => 'ok',
            'data'   => [],
        ]);
    }

    // ----------------------------------------

    private function createErrorResponse(string $message): \Symfony\Component\HttpFoundation\JsonResponse
    {
        return $this->json([
            'status'  => 'error',
            'message' => $message,
        ]);
    }

    // ########################################
}
