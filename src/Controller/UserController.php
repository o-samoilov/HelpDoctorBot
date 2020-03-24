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
     * @param \App\Repository\UserRepository $userRepository
     * @param \App\Repository\CityRepository $cityRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(
        \App\Repository\UserRepository $userRepository,
        \App\Repository\CityRepository $cityRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($Rta['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['description']) || !is_string($data['description'])) {
            return $this->createErrorResponse('Invalid key "description".');
        }

        if (!isset($data['role']) || !in_array($data['role'], [
                \App\Entity\User::ROLE_DRIVER,
                \App\Entity\User::ROLE_DOCTOR,
            ])
        ) {
            return $this->createErrorResponse('Invalid key "role".');
        }

        if (!isset($data['city_id']) || !is_int($data['city_id'])) {
            return $this->createErrorResponse('Invalid key "city_id".');
        }

        $city = $cityRepository->find($data['city_id']);
        if ($city === null) {
            return $this->createErrorResponse('City not found.');
        }

        $user = $userRepository->findByPipeUid($data['pipe_uid']);
        if ($user !== null) {
            return $this->createErrorResponse('User already exist.');
        }

        $pipeUid     = $data['pipe_uid'];
        $description = $data['description'];
        $role        = $data['role'];

        $user = new \App\Entity\User();
        $user->setPipeUid($pipeUid)
             ->setDescription($description)
             ->setCity($city);

        if ($role === \App\Entity\User::ROLE_DRIVER) {
            $user->markRoleDriver();
        } else {
            $user->markRoleDoctor();
        }

        $userRepository->save($user);

        return $this->json([
            'status' => 'ok',
            'data'   => [
                'pipe_uid'    => $user->getPipeUid(),
                'role'        => $user->getRole(),
                'description' => $user->getDescription(),
                'city_id'     => $user->getCity()->getId(),
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
