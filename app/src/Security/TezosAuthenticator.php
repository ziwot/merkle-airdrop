<?php

declare(strict_types=1);

namespace App\Security;

use Bzzhh\Pezos\Keys\PubKey;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class TezosAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    private HttpUtils $httpUtils;
    private $urlGenerator;
    private $csrfTokenManager;

    public function __construct(
        HttpUtils $httpUtils,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->httpUtils        = $httpUtils;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function supports(Request $request): bool
    {
        return 'app_login' === $request->attributes->get('_route') &&
            $request->isMethod('POST');
    }

    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        return $this->redirectToRoute($request, 'app_home');
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ): ?Response {
        if (
            $targetPath = $this->getTargetPath(
                $request->getSession(),
                $providerKey
            )
        ) {
            return new RedirectResponse($targetPath);
        }

        return $this->httpUtils->createRedirectResponse(
            $request,
            'app_home'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function authenticate(Request $request): Passport
    {
        $message   = $request->request->get('msg');
        $signature = $request->request->get('sig');
        $pubKey    = $request->request->get('pubKey');
        $csrfToken = $request->request->get('_csrf_token');

        $pubKey = PubKey::fromBase58($pubKey);

        if (
            !$pubKey->verifySignature($signature, $message) &&
            !$pubKey->verifySignature($signature, bin2hex($message))
        ) {
            throw new \Exception('Signature verification failed');
        }

        return new SelfValidatingPassport(
            new UserBadge($pubKey->getAddress()),
            [new CsrfTokenBadge('authenticate', $csrfToken)]
        );
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_home');
    }

    private function redirectToRoute(Request $request, string $route): Response
    {
        return $this->httpUtils->createRedirectResponse($request, $route);
    }
}
