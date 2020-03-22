<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    // ########################################

    /**
     * @Route("/user/get", methods={"GET"})
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getAction(
        \App\Repository\UserRepository $userRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $pipeUid = $request->get('pipe_uid');

        if ($pipeUid === null) {
            return $this->createErrorResponse('Input data error.');
        }

        $user = $userRepository->findByPipeUid((int)$pipeUid);
        if ($user !== null) {
            return $this->json([
                'status' => 'ok',
                'data'   => [
                    'pipe_uid'     => $user->getPipeUid(),
                    'telegram_uid' => $user->getTelegramUid(),
                    'username'     => $user->getUsername(),
                    'first_name'   => $user->getFirstName(),
                    'last_name'    => $user->getLastName(),
                    'role'         => $user->getRole(),
                    'description'  => $user->getDescription(),
                ],
            ]);
        }

        return $this->createErrorResponse('Not found');
    }

    /**
     * @Route("/user/create", methods={"POST"})
     * @param \App\Entity\User\Factory       $userFactory
     * @param \App\Repository\UserRepository $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(
        \App\Entity\User\Factory $userFactory,
        \App\Repository\UserRepository $userRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['telegram_uid']) || !is_int($data['telegram_uid'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['username']) || !is_string($data['username'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['first_name']) || !is_string($data['first_name'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['last_name']) || !is_string($data['last_name'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['description']) || !is_string($data['description'])) {
            return $this->createErrorResponse('Input data error.');
        }

        if (!isset($data['role']) || !in_array($data['role'], [
                \App\Entity\User::ROLE_DRIVER,
                \App\Entity\User::ROLE_DOCTOR,
            ])
        ) {
            return $this->createErrorResponse('Input data error.');
        }

        $user = $userRepository->findByPipeUid($data['pipe_uid']);
        if ($user !== null) {
            return $this->json([
                'status' => 'ok',
                'data'   => [],
            ]);
        }

        if ($data['role'] === \App\Entity\User::ROLE_DRIVER) {
            $user = $userFactory->createDriver(
                $data['pipe_uid'],
                $data['telegram_uid'],
                $data['username'],
                $data['first_name'],
                $data['last_name'],
                $data['description']
            );
        } else {
            $user = $userFactory->createDoctor(
                $data['pipe_uid'],
                $data['telegram_uid'],
                $data['username'],
                $data['first_name'],
                $data['last_name'],
                $data['description']
            );
        }

        $userRepository->save($user);

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
