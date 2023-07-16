<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SigninController extends AbstractController
{
  #[Route('/signin', methods: ['POST'], name: 'app_login')]
  public function signin(AuthenticationUtils $authenticationUtils): JsonResponse
  {
    $error = $authenticationUtils->getLastAuthenticationError();
    $args = $error === null ? [null, 200] : [['error' => $error], 400];

    return $this->json(...$args);
  }
}
