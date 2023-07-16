<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SigninController extends AbstractController
{
  #[Route('/signin', name: 'app_login')]
  public function signin(AuthenticationUtils $authenticationUtils): JsonResponse
  {
    $error = $authenticationUtils->getLastAuthenticationError();
    $code = $error === null ? 400 : 200;

    return $this->json(['error' => $error], $code);
  }
}
