<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    // ########################################

    /**
     * @Route("/route/create",  methods={"POST"})
     */
    public function createAction(
        \App\Repository\UserRepository $userRepository,
        \App\Repository\CityRepository $cityRepository
    ): \Symfony\Component\HttpFoundation\JsonResponse {
        $request = Request::createFromGlobals();
        $data    = (array)json_decode($request->getContent(), true);

        if (!isset($data['user_id']) || !is_int($data['user_id'])) {
            return $this->createErrorResponse('Invalid key "user_id".');
        }

        if (!isset($data['city_id']) || !is_int($data['city_id'])) {
            return $this->createErrorResponse('Invalid key "city_id".');
        }
        $city = $cityRepository->find($data['city_id']);
        if ($city === null) {
            return $this->createErrorResponse('City not found.');
        }

        if (!isset($data['from_district_id']) || !is_int($data['from_district_id'])) {
            return $this->createErrorResponse('Invalid key "from_district_id".');
        }
        if (!isset($data['comment_from']) || !is_string($data['comment_from'])) {
            return $this->createErrorResponse('Invalid key "comment_from".');
        }

        if (!isset($data['to_district_id']) || !is_int($data['to_district_id'])) {
            return $this->createErrorResponse('Invalid key "to_district_id".');
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
