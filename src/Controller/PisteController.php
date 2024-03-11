<?php

namespace App\Controller;

use App\Entity\Piste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PisteController extends AbstractController
{
    #[Route('/piste', name: 'app_piste')]
    public function index(): Response
    {
        return $this->render('piste/index.html.twig', [
            'controller_name' => 'PisteController',
        ]);
    }

}