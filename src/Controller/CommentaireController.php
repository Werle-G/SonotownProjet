<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
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



    // #[Route('/artiste/{idArtiste}/new/commentaire', name: 'new_commentaire')]
    // public function new_commentaire(Request $request, EntityManagerInterface $entityManager, $idArtiste): Response
    // {
    //     $commentaire = new Commentaire();
    //     $commentaire->setCommenter($this->getUser());
    
    //     $form = $this->createForm(CommentaireType::class, $commentaire);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($commentaire);
    //         $entityManager->flush();
    
    //         return $this->redirectToRoute('artiste_page', ['id' => $idArtiste]);
    //     }
    
    //     return $this->render('artiste_page/commentaire/new.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }


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
