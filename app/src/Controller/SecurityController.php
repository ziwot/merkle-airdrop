<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class SecurityController
{
    #[Route('/login', name: 'app_login')]
    public function login(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the login key on your firewall.');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
