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
                    'pipe_uid'    => $user->getPipeUid(),
                    'role'        => $user->getRole(),
                    'description' => $user->getDescription(),
                ],
            ]);
        }

        return $this->createErrorResponse('Not found');
    }

    /**
     * @Route("/user/create", methods={"POST"})
     * @param \App\Entity\User\Factory       $userFactory
     * @param \App\Repository\UserRepository $userRepository
     * @param \App\Repository\CityRepository $cityRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(
        \App\Entity\User\Factory $userFactory,
        \App\Repository\UserRepository $userRepository,
        \App\Repository\CityRepository $cityRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
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

        if (!isset($data['city_id']) || !is_int($data['city_id'])) {
            return $this->createErrorResponse('Input data error.');
        }

        $city = $cityRepository->find($data['city_id']);
        if ($city === null) {
            return $this->createErrorResponse('Input data error.');
        }

        $user = $userRepository->findByPipeUid($data['pipe_uid']);
        if ($user !== null) {
            return $this->json([
                'status' => 'ok',
                'data'   => [
                    'pipe_uid'    => $user->getPipeUid(),
                    'role'        => $user->getRole(),
                    'description' => $user->getDescription(),
                ],
            ]);
        }

        if ($data['role'] === \App\Entity\User::ROLE_DRIVER) {
            $user = $userFactory->createDriver(
                $data['pipe_uid'],
                $data['description'],
                $city
            );
        } else {
            $user = $userFactory->createDoctor(
                $data['pipe_uid'],
                $data['description'],
                $city
            );
        }

        $userRepository->save($user);

        return $this->json([
            'status' => 'ok',
            'data'   => [
                'pipe_uid'    => $user->getPipeUid(),
                'role'        => $user->getRole(),
                'description' => $user->getDescription(),
            ],
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
