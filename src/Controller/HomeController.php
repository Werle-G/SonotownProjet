<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UserRepository $userRepository, AlbumRepository $albumRepository): Response
    {

        $users = $userRepository->findArtisteHome('["ROLE_ARTISTE"]');
        $albums = $albumRepository->findBy([], ["dateSortieAlbum" => 'ASC'], limit:3);

        // dd($albums);

        return $this->render('home/index.html.twig', [
            'users' => $users,
            'albums' => $albums
        ]);
    }

}
