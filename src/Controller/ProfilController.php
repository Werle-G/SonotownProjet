<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAll();
        return $this->render('profil/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/profil/{id}', name: 'show_profil')]
    public function show(User $user, $id)
    {

        // $artistes = $userRepository->findBy(["nomGenreMusical" => $id]);
        // $albums = $albumRepository->findBy(["genreMusical" => $id]);


        return $this->render('profil/show.html.twig', [
            // 'genreMusical' => $genreMusical,
            // 'albums' => $albums,
            'user' => $user,
        ]);
    }
}
