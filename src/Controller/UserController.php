<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseAbstract
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
        if ($user === null) {
            return $this->createErrorResponse('User not found');
        }

        return $this->json([
            'status' => 'ok',
            'data'   => $this->userToArray($user),
        ]);
    }

    // ########################################

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

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['username']) || !is_string($data['username'])) {
            return $this->createErrorResponse('Invalid key "username".');
        }

        if (!isset($data['first_name']) || !is_string($data['first_name'])) {
            return $this->createErrorResponse('Invalid key "first_name".');
        }

        if (!isset($data['last_name']) || !is_string($data['last_name'])) {
            return $this->createErrorResponse('Invalid key "last_name".');
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

        $pipeUid   = $data['pipe_uid'];
        $role      = $data['role'];
        $username  = $data['username'];
        $firstName = $data['first_name'];
        $lastName  = !empty($data['last_name']) ? $data['last_name'] : null;

        $user = new \App\Entity\User();
        $user->setPipeUid($pipeUid)
             ->setUsername($username)
             ->setFirstName($firstName)
             ->setLastName($lastName)
             ->setCity($city);

        if ($role === \App\Entity\User::ROLE_DRIVER) {
            $user->markRoleDriver();
        } else {
            $user->markRoleDoctor();
        }

        $userRepository->save($user);

        return $this->json([
            'status' => 'ok',
            'data'   => $this->userToArray($user),
        ]);
    }

    // ########################################

    // ########################################

    /**
     * @Route("/user/update", methods={"PUT"})
     *
     * @param \App\Repository\UserRepository  $userRepository
     * @param \App\Repository\RouteRepository $routeRepository
     * @param \App\Repository\CityRepository  $cityRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(
        \App\Repository\UserRepository $userRepository,
        \App\Repository\RouteRepository $routeRepository,
        \App\Repository\CityRepository $cityRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        $user = $userRepository->findByPipeUid($data['pipe_uid']);
        if ($user === null) {
            return $this->createErrorResponse('User not found.');
        }

        $isNeedUpdate = false;

        if (isset($data['username']) &&
            is_string($data['username']) &&
            $user->getUsername() !== $data['username']
        ) {
            $user->setUsername($data['username']);
            $isNeedUpdate = true;
        }

        if (isset($data['first_name']) &&
            is_string($data['first_name']) &&
            $user->getFirstName() !== $data['first_name']
        ) {
            $user->setFirstName($data['first_name']);
            $isNeedUpdate = true;
        }

        if (isset($data['last_name']) &&
            is_string($data['last_name']) &&
            $user->getLastName() !== $data['last_name']
        ) {
            $user->setLastName($data['last_name']);
            $isNeedUpdate = true;
        }

        if (isset($data['role']) &&
            in_array($data['role'], [
                \App\Entity\User::ROLE_DRIVER,
                \App\Entity\User::ROLE_DOCTOR,
            ]) &&
            $user->getRole() !== $data['role']
        ) {
            $newRole = (int)$data['role'];
            if ($newRole === \App\Entity\User::ROLE_DRIVER) {
                $user->markRoleDriver();
            } else {
                $routes = $routeRepository->findByUser($user);
                foreach ($routes as $route) {
                    $route->markInactive();
                    $routeRepository->save($route);
                }

                $user->markRoleDoctor();
            }
            $isNeedUpdate = true;
        }

        if (isset($data['city_id']) &&
            is_int($data['city_id'])
        ) {
            $city = $cityRepository->find($data['city_id']);
            if ($city === null) {
                return $this->createErrorResponse('City not found.');
            }

            if ($user->getCity()->getId() !== $city->getId()) {
                $user->setCity($city);
                $isNeedUpdate = true;
            }
        }

        if (isset($data['phone']) &&
            is_string($data['phone']) &&
            $user->getPhone() !== $data['phone']
        ) {
            $user->setPhone($data['phone']);
            $isNeedUpdate = true;
        }

        $isNeedUpdate && $userRepository->save($user);

        return $this->json([
            'status' => 'ok',
            'data'   => $this->userToArray($user),
        ]);
    }

    // ########################################

    private function userToArray(\App\Entity\User $user): array
    {
        $fullName = $user->getFirstName();
        if ($user->hasLastName()) {
            $fullName .= " {$user->getLastName()}";
        }

        return [
            'pipe_uid'   => $user->getPipeUid(),
            'username'   => $user->getUsername(),
            'full_name'  => $fullName,
            'first_name' => $user->getFirstName(),
            'lastName'   => $user->getLastName(),
            'phone'      => $user->getPhone(),
            'role'       => $user->getRole(),
            'city_id'    => $user->getCity()->getId(),
        ];
    }

    // ########################################

    /**
     * @Route("/user/sendProfile", methods={"POST"})
     * @param \App\Repository\UserRepository      $userRepository
     * @param \App\Model\Pipe\Command\SendMessage $pipeSendMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendProfileAction(
        \App\Repository\UserRepository $userRepository,
        \App\Model\Pipe\Command\SendMessage $pipeSendMessage
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        $user = $userRepository->findByPipeUid($data['pipe_uid']);
        if ($user === null) {
            return $this->createErrorResponse('User not found.');
        }

        $pipeSendMessage->setUid($user->getPipeUid())
                        ->setMessage($this->generateProfileText($user));

        $pipeSendMessage->process();

        return $this->json([
            'status' => 'ok',
        ]);
    }

    // ########################################

    private function generateProfileText(\App\Entity\User $user): string
    {
        if ($user->isRoleDriver()) {
            $roleText = 'Водій';
        } else {
            $roleText = 'Лікар/Працівник екстрених служб';
        }

        $fullName = $user->getFirstName();
        if ($user->hasLastName()) {
            $fullName .= " {$user->getLastName()}";
        }

        $phone = $user->hasPhone() ? $user->getPhone() : '-';

        return <<<TEXT
▶️Роль: {$roleText}
👱‍♂️Ім'я: {$fullName}
✉️Telegram: @{$user->getUsername()}
☎️Телефон: {$phone}
🏙️Місто: {$user->getCity()->getName()}
TEXT;
    }

    // ########################################
}
