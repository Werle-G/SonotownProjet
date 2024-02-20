<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtisteController extends AbstractController
{
    #[Route('/artiste', name: 'app_artiste')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAllUser('["ROLE_ARTISTE"]');

        return $this->render('artiste/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/artiste/{id}', name: 'show_artiste')]
    public function show(User $user, AlbumRepository $albumRepository, PostRepository $postRepository, $id):Response
    {

        $albums = $albumRepository->findBy(["user" => $id]);
        $posts = $postRepository->findBy(["user" => $id]);

        return $this->render('artiste/show.html.twig', [
            'user' => $user,
            'albums' => $albums,
            'posts' => $posts,
        ]);
    }

}
