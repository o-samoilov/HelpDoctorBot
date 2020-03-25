<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\AccessToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private $em;

    // ########################################

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // ########################################

    public function supports(Request $request)
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        if ($request->headers->has('X-AUTH-TOKEN')) {
            return $request->headers->get('X-AUTH-TOKEN');
        }

        if ($request->headers->has('HTTP-X-AUTH-TOKEN')) {
            return $request->headers->get('HTTP-X-AUTH-TOKEN');
        }

        return '';
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials || empty($credentials)) {
            return null;
        }

        return $this->em->getRepository(AccessToken::class)
                        ->findOneBy(['token' => $credentials]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    // ########################################
}
