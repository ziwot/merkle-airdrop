<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberAreaController extends AbstractController
{
    #[Route('/member/area', name: 'app_member_area')]
    public function index(): Response
    {
        return $this->render('member_area/index.html.twig', [
            'controller_name' => 'MemberAreaController',
        ]);
    }
}
