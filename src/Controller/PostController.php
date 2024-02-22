<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/new', name: 'new_post')]
    #[Route('/post/{id}/edit', name: 'edit_post')]
    public function new_edit(Post $post = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$post) {
            $post = new Post();  
        } 

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $post = $form->getData();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/new.html.twig', [
            'formAddPost' => $form,
            'edit' => $post->getId()
        ]);
    }

    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function delete(Post $post, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    #[Route('/post/{id}', name: 'show_post')]
    public function show(User $user, PostRepository $postRepository):Response
    {

        $posts = $postRepository->findBy(["user" => $user]);
        
        return $this->render('post/show.html.twig', [
            'user' => $this->getUser(),
            'posts' => $posts,
        ]);
    }
}
