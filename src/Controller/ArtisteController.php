<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(UserRepository $UserRepository): Response
    {
            // $entreprises = $entityManager->getRepository(Entreprise::class)->findAll();
        // $entreprises = $entrepriseRepository->findAll();

        // SELECT * FROM entreprise ORDER BY raisonSociale

        // SELECT * FROM entreprise WHERE ville = 'Strasbourg' ORDER BY raisonSociale ASC
        // $entreprises = $entrepriseRepository->findBy(["ville" => "Strasbourg"], ["raisonSociale" => "ASC"]);

        $users = $UserRepository->findAll();
        return $this->render('artiste/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/artiste/{id}', name: 'show_artiste')]
    public function show(User $user, AlbumRepository $albumRepository, $id):Response
    {

        $albums = $albumRepository->findBy(["user" => $id]);

        return $this->render('artiste/show.html.twig', [
            'user' => $user,
            'albums' => $albums,
        ]);
    }

}
