<?php

namespace App\Controller;

use App\Entity\GenreMusical;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\GenreMusicalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class GenreMusicalController extends AbstractController
{
    #[Route('/genre/musical', name: 'all_genre_musical')]
    public function index(GenreMusicalRepository $genreMusicalRepository): Response
    {

        $genreMusicals = $genreMusicalRepository->findAll();
        return $this->render('genre_musical/index.html.twig', [
            'genreMusicals' => $genreMusicals,
        ]);
    }

    #[Route('/genre/musical/detail/{id}', name: 'detail_genre_musical')]
    public function detail_genre_musical(GenreMusical $genreMusical, UserRepository $userRepository, AlbumRepository $albumRepository, $id)
    {

        $albums = $albumRepository->findBy(["genreMusicals" => $id]);
        $users = $userRepository->findBy(["genreMusical" => $id]);

        return $this->render('genre_musical/show.html.twig', [
            'genreMusical' => $genreMusical,
            'albums' => $albums,
            'users' => $users,
        ]);
    }
    
}
