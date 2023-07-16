<?php

namespace App\Security;

use App\Repository\MemberRepository;
use Bzzhh\Pezos\Keys\PubKey;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class TezosAuthenticator extends AbstractAuthenticator
{
  public function __construct(private MemberRepository $memberRepository)
  {
  }

  /**
   * Called on every request to decide if this authenticator should be
   * used for the request. Returning `false` will cause this authenticator
   * to be skipped.
   */
  public function supports(Request $request): ?bool
  {
    return 'app_login' === $request->attributes->get('_route') &&
      $request->isMethod('POST');
  }

  public function authenticate(Request $request): Passport
  {
    list($pk, $pkh, $message, $signature, $csrfToken) = array_values($request->toArray());

    $pubKey = PubKey::fromBase58($pk);

    if (
      !$pubKey->verifySignature($signature, $message) &&
      !$pubKey->verifySignature($signature, bin2hex($message))
    ) {
      throw new \Exception('Signature verification failed');
    }

    // try {
    //     $this->application->getOneMemberByPubKey($pubKey->getPublicKey());
    // } catch (CouldNotFindMember $exception) {
    //     $this->application->requestAccess(
    //         new RequestAccess(
    //             $pubKey->getPublicKey(),
    //             $pubKey->getAddress()
    //         )
    //     );
    // }

    return new SelfValidatingPassport(
      new UserBadge(
        $pkh,
        fn (string $userIdentifier): ?UserInterface =>
        $this->memberRepository->findOneBy(['address' => $userIdentifier])
      ),
      [new CsrfTokenBadge('authenticate', $csrfToken)]
    );
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    // on success, let the request continue
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
  {
    $data = [
      // you may want to customize or obfuscate the message first
      'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

      // or to translate this message
      // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
    ];

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}
