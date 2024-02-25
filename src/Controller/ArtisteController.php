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

#[Route('/artiste', name: 'artiste_')]
class ArtisteController extends AbstractController
{
    #[Route('/', name: 'all_artiste')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAllUser('["ROLE_ARTISTE"]');

        return $this->render('artiste/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/{id}', name: 'page_artiste')]
    public function show(User $user, PostRepository $postRepository, $id):Response
    {

        $posts = $postRepository->findBy(["user" => $id]);

        return $this->render('artiste/page.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

}
