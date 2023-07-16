<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AirdropRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
  public function __construct(private AirdropRepository $airdropRepository)
  {
  }

  #[Route('/', name: 'app_home')]
  public function index(): Response
  {
    $recentAirdrops = $this->airdropRepository->recentAirdrops();

    return $this->render('home/index.html.twig', [
      'recentAirdrops' => $recentAirdrops,
    ]);
  }
}
