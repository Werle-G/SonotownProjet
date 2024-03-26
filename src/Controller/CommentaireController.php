<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManager;
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

    // Fonction pour crée un commentaire sur la page artiste
    #[Route('/user/{userId}/commentaire/new/{artisteId}', name: 'new_commentaire')]
    public function newCommentaire(
        $userId,
        $artisteId,
        Commentaire $commentaire = null, 
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // On récupère l'utilisateur de la session en récupérant son id
        $userSession = $userRepository->find($userId);

        // On récupère l'artiste en récupérant son id
        $artiste = $userRepository->find($artisteId);

        // On récupère le message via la méthode get de la classe Request
        $addCommentaire = $request->get('message');

            // Si userSession ainsi que artiste et addCommentaire existent
            if($userSession && $artiste && $addCommentaire){

                // On crée un nouvel objet commentaire
                $commentaire = new Commentaire();

                // On attribut l'utilisateur de la session au commentaire
                $commentaire->setCommenter($userSession);

                // On attribut l'artiste au commentaire
                $commentaire->setRepondre($artiste);

                // On attribut le message au commentaire
                $commentaire->setMessage($addCommentaire);

                // On prepare la base de donnée 
                $entityManager->persist($commentaire);
                
                // On execute la requête
                $entityManager->flush();

                // On redirige l'utilisateur vers la page de l'artiste en prenant en parmètre l'id de l'artiste
                return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);

            }

        // On retourne la vue de la page de l'artiste
        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]); 
        
    }

    // Fonction répondre commentaire
    #[Route('/user/{userId}/commentaire/new/repondre/{commentaireId}', name: 'new_commentaire_repondre')]
    public function newCommentaireRepondre(
        $userId,
        $commentaireId,
        Commentaire $commentaire = null, 
        CommentaireRepository $commentaireRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $userRepository->findOneById($userId);
        
        $commentaireBdd = $commentaireRepository->find($commentaireId);

        $commentaireBdd = $commentaireBdd->getId();

        dd($commentaire->getCommenter());

        // $userRepondre = $commentaireBdd->getCommenter()->getId();

        $addCommentaire = $request->get('message');
        
            if($userSession && $commentaireBdd && $addCommentaire){

                $commentaire = new Commentaire();

                $commentaire->setCommenter($userSession);

                // $commentaire->setRepondre($artiste);
                $commentaire->setMessage($addCommentaire);
                
                $entityManager->persist($commentaire);
                
                $entityManager->flush();

                return $this->redirectToRoute('artiste_page', ['artisteId' => $userId]);

            }

        return $this->redirectToRoute('artiste_page', ['artisteId' => $userId]); 
        
    }

    // Fonction pour effacer un commentaire
    // L'artiste peut supprimer un commentaire 
    // L'utilisateur ayant crée le commentaire peut supprimer le commentaire 
    // L'administrateur peut supprimer le commentaire
    #[Route('/user/{userId}/commentaire/{commentaireId}/delete', name: 'delete_commentaire')]
    public function delete(
        $userId,
        $commentaireId,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        CommentaireRepository $commentaireRepository, 
    ): Response
    {
        
        $user = $userRepository->find($userId);

        $commentaire = $commentaireRepository->find($commentaireId);

        $artisteId = $commentaire->getRepondre()->getId();

        $user = $commentaire->getCommenter();

        $user->removeCommentaire($commentaire);

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);
    }

    // Fonction pour bannir un commentaire
    // Cette fonction cache le message du commentaire
    #[Route('/commentaire/{commentaireId}/ban', name: 'ban_commentaire')]
    public function banCommentaire(
        $commentaireId,
        EntityManagerInterface $entityManager,
        CommentaireRepository $commentaireRepository
    ): Response
    {

        $commentaire = $commentaireRepository->find($commentaireId);

        $artisteId = $commentaire->getRepondre()->getId();

        $commentaire->setBan(true);

        $entityManager->persist($commentaire);

        $entityManager->flush();

        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);
    }

    // Fonction pour unban un commentaire
    #[Route('/commentaire/{commentaireId}/unban', name: 'unban_commentaire')]
    public function unbanCommentaire(
        $commentaireId,
        EntityManagerInterface $entityManager,
        CommentaireRepository $commentaireRepository
    ): Response
    {

        $commentaire = $commentaireRepository->find($commentaireId);

        $artisteId = $commentaire->getRepondre()->getId();

        $commentaire->setBan(false);

        $entityManager->persist($commentaire);

        $entityManager->flush();


        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);
    }


}
