<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
use App\Repository\ConcertRepository;
use App\Repository\GenreMusicalRepository;
use App\Repository\PisteRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{


    // Fonction qui affiche la page de recherche
    #[Route('/search', name: 'app_search')]
    public function index(
        GenreMusicalRepository $genreMusicalRepository,
        UserRepository $userRepository,
        AlbumRepository $albumRepository,
        PisteRepository $pisteRepository,
        ConcertRepository $concertRepository,
        Request $request
    ): Response
    {

        $artistes = $userRepository->findUserByRole('["ROLE_ARTISTE"]');

        $genreMusicals = $genreMusicalRepository->findAll();


        // On récupère les filtres
        $filters = $request->get("genreMusicals");
        // dd($filters);


        $albums = $albumRepository->findAll();

        $albums = $albumRepository->findAlbums($filters);
        dd($albums);

        // On vérifie si on a une requête Ajax
        if($request->get('ajax')){
            return "ok";
        }


        return $this->render('search/index.html.twig', [
            'artistes' => $artistes,
            'genreMusicals' => $genreMusicals,
            'albums' => $albums
        ]);
    }
    
}
