<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\AuthToken;
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
    private const AUTH_HEADER_NAME = 'X-AUTH-TOKEN';

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
        $authToken = null;
        if ($request->headers->has(self::AUTH_HEADER_NAME)) {
            $authToken = $request->headers->get(self::AUTH_HEADER_NAME);
        }

        if ($request->headers->has('HTTP-' . self::AUTH_HEADER_NAME)) {
            $authToken = $request->headers->get('HTTP-' . self::AUTH_HEADER_NAME);
        }

        return ['auth_token' => $authToken];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (null === $credentials['auth_token']) {
            return null;
        }

        return $this->em->getRepository(AuthToken::class)
                        ->findOneBy(['uuid' => self::AUTH_HEADER_NAME]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $user->getPassword() === $credentials['auth_token'];
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
            'message' => 'Authentication Required',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function getMessageKey()
    {
        return 'Username could not be found.';
    }

    // ########################################
}
