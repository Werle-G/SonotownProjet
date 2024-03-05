<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReseauController extends AbstractController
{
    #[Route('/reseau', name: 'app_reseau')]
    public function index(): Response
    {
        return $this->render('reseau/index.html.twig', [
            'controller_name' => 'ReseauController',
        ]);
    }

}
