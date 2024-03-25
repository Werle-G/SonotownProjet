<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }


    #[Route('/user/{userId}/commentaire/new/{userCommentId}', name: 'new_commentaire')]
    public function newCommentaire(
        $userId,
        $userCommentId,
        Commentaire $commentaire = null, 
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $userRepository->findOneById($userId);
        
        $artiste = $userRepository->find($userCommentId);

        $slug = $artiste->getSlug();

        $addCommentaire = $request->get('message');
        
            if($userSession && $artiste && $addCommentaire){

                $commentaire = new Commentaire();

                $commentaire->setCommenter($userSession);
                $commentaire->setMessage($addCommentaire);
                
                $entityManager->persist($commentaire);
                $entityManager->flush();

                return $this->redirectToRoute('artiste_page', ['slug' => $artiste->getSlug()]);

            }

        return $this->redirectToRoute('artiste_page', ['slug' => $slug]); 
        
    }


    // #[Route('/commentaire/{id}/delete', name: 'delete_commentaire')]
    // public function delete(Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    // {
    //     $entityManager->remove($commentaire);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('app_home');
    // }

    // #[Route('/commentaire/{id}', name: 'show_commentaire')]
    // public function show(User $user, CommentaireRepository $commentaireRepository):Response
    // {

    //     $commentaires = $commentaireRepository->findBy(["user" => $user]);
        
    //     return $this->render('commentaire/show.html.twig', [
    //         'user' => $this->getUser(),
    //         'commentaires' => $commentaires,
    //     ]);
    // }


}
