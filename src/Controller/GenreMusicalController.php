<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GenreMusical;
use App\Repository\AlbumRepository;
use App\Repository\GenreMusicalRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreMusicalController extends AbstractController
{
    #[Route('/genre/musical', name: 'app_genre_musical')]
    public function index(GenreMusicalRepository $genreMusicalRepository): Response
    {

        $genreMusicals = $genreMusicalRepository->findAll();
        return $this->render('genre_musical/index.html.twig', [
            'genreMusicals' => $genreMusicals,
        ]);
    }

    #[Route('/genre/musical/{id}', name: 'show_genre_musical')]
    public function show(User $users, AlbumRepository $albumRepository, GenreMusical $genreMusical, $id)
    {

        // $artistes = $userRepository->findBy(["nomGenreMusical" => $id]);
        $albums = $albumRepository->findBy(["genreMusical" => $id]);

        return $this->render('genre_musical/show.html.twig', [
            'genreMusical' => $genreMusical,
            'albums' => $albums,
            'users' => $users,
        ]);
    }
}
