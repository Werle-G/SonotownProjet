<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    // Dans le contexte d'un serveur local l'adresse http://127.0.0.1:8000 suivit d'un slash représente la racine du site web
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, AlbumRepository $albumRepository): Response
    {

        // La fonction findArtisteHome présente dans UserRepository récupère les 5 derniers artistes inscrits. Artistes qui sont présent dans la page home, section "Les nouveaux artistes".
        $users = $userRepository->findAllUserByDate('["ROLE_ARTISTE"]');

        // La méthode findBy prend en premier argument un critère, un ordre de recherche et permet de limiter le nombre d'objets souhaité.
        $albums = $albumRepository->findBy([], ["dateSortieAlbum" => 'ASC'], limit:8);

        return $this->render('home/index.html.twig', [
            'users' => $users,
            'albums' => $albums
        ]);
    }

}
