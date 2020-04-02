<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends BaseAbstract
{
    private const MAX_PASSENGERS_COUNT = 6;
    private const MAX_ROUTE_COUNT      = 15;
    private const SEND_LIMIT           = 3;

    // ########################################

    /**
     * @Route("/route/create",  methods={"POST"})
     *
     * @param \App\Repository\RouteRepository    $routeRepository
     * @param \App\Repository\UserRepository     $userRepository
     * @param \App\Repository\DistrictRepository $districtRepository
     * @param \App\Repository\CityRepository     $cityRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(
        \App\Repository\RouteRepository $routeRepository,
        \App\Repository\UserRepository $userRepository,
        \App\Repository\DistrictRepository $districtRepository,
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

        if (!$user->isRoleDriver()) {
            return $this->createErrorResponse('User role invalid.');
        }

        if (!isset($data['city_id']) || !is_int($data['city_id'])) {
            return $this->createErrorResponse('Invalid key "city_id".');
        }

        $city = $cityRepository->find($data['city_id']);
        if ($city === null) {
            return $this->createErrorResponse('City not found.');
        }

        if ($user->getCity()->getId() !== $city->getId()) {
            return $this->createErrorResponse('Invalid city, user has another city.');
        }

        if (!isset($data['from_district_id']) || !is_int($data['from_district_id'])) {
            return $this->createErrorResponse('Invalid key "from_district_id".');
        }

        $fromDistrict = $districtRepository->findByIdAndCity($data['from_district_id'], $city);
        if ($fromDistrict === null) {
            return $this->createErrorResponse('From district not found.');
        }

        if (!isset($data['comment_from']) || !is_string($data['comment_from'])) {
            return $this->createErrorResponse('Invalid key "comment_from".');
        }

        if (!isset($data['to_district_id']) || !is_int($data['to_district_id'])) {
            return $this->createErrorResponse('Invalid key "to_district_id".');
        }

        $toDistrict = $districtRepository->findByIdAndCity($data['to_district_id'], $city);
        if ($toDistrict === null) {
            return $this->createErrorResponse('To district not found.');
        }

        if (!isset($data['comment_to']) || !is_string($data['comment_to'])) {
            return $this->createErrorResponse('Invalid key "comment_to".');
        }

        if (!isset($data['time']) || !is_string($data['time'])) {
            return $this->createErrorResponse('Invalid key "time".');
        }

        if (!isset($data['date']) || !is_string($data['date'])) {
            return $this->createErrorResponse('Invalid key "date".');
        }

        if (
            !isset($data['passengers_count']) ||
            !is_int($data['passengers_count']) ||
            $data['passengers_count'] < 1 ||
            $data['passengers_count'] > self::MAX_PASSENGERS_COUNT
        ) {
            return $this->createErrorResponse('Invalid key "passengers_count".');
        }

        $routes = $routeRepository->findByUser($user);
        if (count($routes) > self::MAX_ROUTE_COUNT) {
            return $this->createErrorResponse('Limit max routes.');
        }

        $commentFrom     = $data['comment_from'];
        $commentTo       = $data['comment_to'];
        $time            = $data['time'];
        $date            = $data['date'];
        $passengersCount = $data['passengers_count'];

        $route = new \App\Entity\Route();
        $route->setFromDistrict($fromDistrict)
              ->setFromComment($commentFrom)
              ->setToDistrict($toDistrict)
              ->setToComment($commentTo)
              ->setTime($time)
              ->setDate($date)
              ->setPassengersCount($passengersCount)
              ->setCity($city)
              ->setUser($user)
              ->markActive();

        $routeRepository->save($route);

        return $this->json([
            'status' => 'ok',
            'data'   => [],
        ]);
    }

    // ########################################

    /**
     * @Route("/route/driver/send",  methods={"POST"})
     *
     * @param \App\Repository\RouteRepository     $routeRepository
     * @param \App\Repository\UserRepository      $userRepository
     * @param \App\Model\Pipe\Command\SendMessage $pipeSendMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendDriverAction(
        \App\Repository\RouteRepository $routeRepository,
        \App\Repository\UserRepository $userRepository,
        \App\Model\Pipe\Command\SendMessage $pipeSendMessage
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['offset'])) {
            return $this->createErrorResponse('Invalid key "offset".');
        }

        $offset = (int)$data['offset'];

        $user = $userRepository->findByPipeUid(($data['pipe_uid']));
        if ($user === null) {
            return $this->createErrorResponse('User not found');
        }

        if (!$user->isRoleDriver()) {
            return $this->createErrorResponse('Invalid user role');
        }

        $routes           = $routeRepository->findByUser($user);
        $totalRoutesCount = count($routes);

        $pipeSendMessage->setUid($user->getPipeUid());

        if ($totalRoutesCount === 0) {
            $pipeSendMessage->setMessage('У вас немає маршрутів, додайте перший маршрут вже зараз!');
            $pipeSendMessage->process();

            return $this->json([
                'status' => 'ok',
                'offset' => 0,
            ]);
        }

        if ($totalRoutesCount <= $offset) {
            return $this->json([
                'status' => 'ok',
                'offset' => 0,
            ]);
        }

        $routes = array_slice($routes, $offset, self::SEND_LIMIT);

        foreach ($routes as $route) {
            $status        = $route->isActive() ? 'Активний' : 'Неактивний';
            $statusCommand = $route->isActive() ? "Деактивувати: /deactivate_route_{$route->getId()}" :
                "Активувати: /activate_route_{$route->getId()}";

            $pipeSendMessage->setMessage(<<<TEXT
▶️Із району: {$route->getFromDistrict()->getName()}
📋Комментарій: {$route->getFromComment()}

▶️До району: {$route->getToDistrict()->getName()}
📋Комментарій: {$route->getToComment()}

🕔Час: {$route->getTime()}
📅Дата: {$route->getDate()}

🙋‍♀️Кількість пасажирів: {$route->getPassengersCount()}

Статус: {$status}
{$statusCommand}

Видалити: /delete_route_{$route->getId()}
TEXT
            );

            $pipeSendMessage->process();
        }

        return $this->json([
            'status' => 'ok',
            'offset' => $offset + self::SEND_LIMIT >= $totalRoutesCount ? 0 : $offset + self::SEND_LIMIT,
        ]);
    }

    // ########################################

    /**
     * @Route("/route/doctor/send",  methods={"POST"})
     *
     * @param \App\Repository\RouteRepository     $routeRepository
     * @param \App\Repository\UserRepository      $userRepository
     * @param \App\Model\Pipe\Command\SendMessage $pipeSendMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendDoctorAction(
        \App\Repository\RouteRepository $routeRepository,
        \App\Repository\UserRepository $userRepository,
        \App\Model\Pipe\Command\SendMessage $pipeSendMessage
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['offset'])) {
            return $this->createErrorResponse('Invalid key "offset".');
        }

        $offset = (int)$data['offset'];

        $user = $userRepository->findByPipeUid(($data['pipe_uid']));
        if ($user === null) {
            return $this->createErrorResponse('User not found');
        }

        if (!$user->isRoleDoctor()) {
            return $this->createErrorResponse('Invalid user role');
        }

        $queryBuilder = $routeRepository->createQueryBuilder('route')
                                        ->where('route.isActive=true');

        if (isset($data['district_from']) && is_int($data['district_from'])) {
            $queryBuilder->where("fromDistrict={$data['district_from']}");
        }

        if (isset($data['district_to']) && is_int($data['district_to'])) {
            $queryBuilder->where("toDistrict={$data['district_to']}");
        }

        $queryBuilder->select('COUNT(route.id) as count');
        $routesCount = (int)$queryBuilder->getQuery()->getSingleScalarResult();

        $pipeSendMessage->setUid($user->getPipeUid());

        if ($routesCount === 0) {
            $pipeSendMessage->setMessage('У вас немає маршрутів, додайте перший маршрут вже зараз!');
            $pipeSendMessage->process();

            return $this->json([
                'status' => 'ok',
                'offset' => 0,
            ]);
        }

        if ($routesCount <= $offset) {
            return $this->json([
                'status' => 'ok',
                'offset' => 0,
            ]);
        }

        $queryBuilder->select('route')
                     ->setFirstResult($offset)
                     ->setMaxResults(self::SEND_LIMIT);

        /** @var \App\Entity\Route[] $routes */
        $routes = $queryBuilder->getQuery()->execute();

        foreach ($routes as $route) {
            $driver = $route->getUser();

            $driverFullName = $driver->getFirstName();
            if ($driver->hasLastName()) {
                $driverFullName .= " {$driver->getLastName()}";
            }

            $driverPhone = $driver->hasPhone() ? $driver->getPhone() : '-';

            $pipeSendMessage->setMessage(<<<TEXT
▶️Із району: {$route->getFromDistrict()->getName()}
📋Комментарій: {$route->getFromComment()}

▶️До району: {$route->getToDistrict()->getName()}
📋Комментарій: {$route->getToComment()}

🕔Час: {$route->getTime()}
📅Дата: {$route->getDate()}

🙋‍♀️Кількість пасажирів: {$route->getPassengersCount()}

Водій🚘
👱‍♂️Ім'я: {$driverFullName}
✉️Telegram: @{$driver->getUsername()}
☎️Телефон: {$driverPhone}
TEXT
            );

            $pipeSendMessage->process();
        }

        return $this->json([
            'status' => 'ok',
            'offset' => $offset + self::SEND_LIMIT >= $routesCount ? 0 : $offset + self::SEND_LIMIT,
        ]);
    }

    // ########################################

    /**
     * @Route("/route/update",  methods={"PUT"})
     *
     * @param \App\Repository\UserRepository  $userRepository
     * @param \App\Repository\RouteRepository $routeRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(
        \App\Repository\UserRepository $userRepository,
        \App\Repository\RouteRepository $routeRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['route_id']) || !is_int($data['route_id'])) {
            return $this->createErrorResponse('Invalid key "route_id".');
        }

        $user = $userRepository->findByPipeUid(($data['pipe_uid']));
        if ($user === null) {
            return $this->createErrorResponse('User not found');
        }

        $route = $routeRepository->find(($data['route_id']));
        if ($route === null) {
            return $this->createErrorResponse('Route not found');
        }

        if ($route->getUser()->getId() !== $user->getId()) {
            return $this->createErrorResponse('Route not register by current user');
        }

        $isNeedUpdate = false;

        if (isset($data['route_status'])) {
            $newRouteStatus = (int)$data['route_status'];
            if (!$route->isActive() && $newRouteStatus === 1) {
                $route->markActive();
                $isNeedUpdate = true;
            }

            if ($route->isActive() && $newRouteStatus === 0) {
                $route->markInactive();
                $isNeedUpdate = true;
            }
        }

        $isNeedUpdate && $routeRepository->save($route);

        return $this->json([
            'status' => 'ok',
        ]);
    }

    // ########################################

    /**
     * @Route("/route/delete",  methods={"DELETE"})
     *
     * @param \App\Repository\RouteRepository $routeRepository
     * @param \App\Repository\UserRepository  $userRepository
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAction(
        \App\Repository\RouteRepository $routeRepository,
        \App\Repository\UserRepository $userRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['pipe_uid']) || !is_int($data['pipe_uid'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        if (!isset($data['route_id']) || !is_int($data['route_id'])) {
            return $this->createErrorResponse('Invalid key "pipe_uid".');
        }

        $user = $userRepository->findByPipeUid(($data['pipe_uid']));
        if ($user === null) {
            return $this->createErrorResponse('User not found');
        }

        $route = $routeRepository->find($data['route_id']);
        if ($route === null) {
            return $this->createErrorResponse('Route not found');
        }

        if ($route->getUser()->getId() !== $user->getId()) {
            return $this->createErrorResponse('Route not register by current user');
        }

        $routeRepository->delete($route);

        return $this->json([
            'status' => 'ok',
        ]);
    }

    // ########################################
}
