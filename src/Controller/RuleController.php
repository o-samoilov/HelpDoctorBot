<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RuleController extends BaseAbstract
{
    // ########################################

    /**
     * @Route("/rule/send",  methods={"POST"})
     *
     * @param \App\Repository\UserRepository      $userRepository
     * @param \App\Model\Pipe\Command\SendMessage $pipeSendMessage
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(
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
                        ->setMessage($this->getRules());

        $pipeSendMessage->process();

        return $this->json([
            'status' => 'ok',
        ]);
    }

    // ########################################

    private function getRules(): string
    {
        return <<<TEXT
Як це працює?

🚘Водії, які можуть БЕЗКОШТОВНО підвезти працівників екстрених служб до роботи, реєструють маршрут за яким прямують.
🦺Працівники екстрених служб можуть знайти маршрут, який їм підходить, та написати водію і домовитись, щоб водій їх підвіз.

Правила поїздки:
✅😷усі повинні бути в масках!
✅ дотримуйтесь правил гігієни! Обов'язково мийте руки💧🧼🤲
✅📄 працівник екстрених служб повинен мати посвідчення і показати водію (це потрібно, щоб не було шахраїв)

🚫🤦не торкайтеся обличчя
🚫🤝відкажіться від рукостискань
🚫 🏟 не беріть повну машину людей
TEXT;
    }

    // ########################################
}
