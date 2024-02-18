<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_album')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    #[Route('/album/{id}', name: 'show_album')]
    public function show(Album $album, PisteRepository $pisteRepository, User $user, $id):Response
    {

        $pistes = $pisteRepository->findBy(["albums" => $id]);
        
        return $this->render('album/show.html.twig', [
            'user' => $user,
            'albums' => $album,
            'pistes' => $pistes,
        ]);
    }
}
