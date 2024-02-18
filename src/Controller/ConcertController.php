<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Repository\ConcertRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConcertController extends AbstractController
{
    #[Route('/concert', name: 'app_concert')]
    public function index(ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->findAll();
        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    #[Route('/concert/{id}', name: 'show_concert')]
    public function show(Concert $concert): Response
    {
        // $concerts = $concertRepository->findAll();
        return $this->render('concert/index.html.twig', [
            'concert' => $concert,
        ]);
    }
}
