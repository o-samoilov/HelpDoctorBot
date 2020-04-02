<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PipeController extends BaseAbstract
{
    /**
     * @Route("/pipe/clear/environment", methods={"POST"})
     *
     * @param \App\Repository\UserRepository      $userRepository
     * @param \App\Model\Pipe\Command\GetUserVars $pipGetUserVars
     *
     * @param \App\Model\Pipe\Command\SetUserVar  $pipSetUserVar
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function clearEnvironmentAction(
        \App\Repository\UserRepository $userRepository,
        \App\Model\Pipe\Command\GetUserVars $pipGetUserVars,
        \App\Model\Pipe\Command\SetUserVar $pipSetUserVar
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

        $pipGetUserVars->setUid($user->getPipeUid());
        $response = $pipGetUserVars->process();

        if (!$response->isSuccess()) {
            return $this->createErrorResponse('Can\'t get user vars.');
        }

        $pipSetUserVar->setUid($user->getPipeUid());
        foreach ($response->getData() as $varName => $varValue) {
            if (strpos($varName, 'sys.') === false) {
                $pipSetUserVar->setVarname($varName)
                              ->setVarvalue('null');

                $pipSetUserVar->process();
            }
        }

        return $this->json([
            'status' => 'ok',
        ]);
    }
}
