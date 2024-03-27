<?php

namespace App\Controller;

use App\Entity\Commentaire;
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
        if($request->isMethod("POST")) {

            // $request->request->get('message') : récupère les données via la méthode POST
            $message = $request->request->get('message');

            // Si userSession, artiste et addCommentaire existent en rentre dans la condition
            if($userSession && $artiste && $message) {

                // On crée un nouvel objet commentaire
                $commentaire = new Commentaire();

                // On attribut l'utilisateur de la session au commentaire
                $commentaire->setCommenter($userSession);

                // On attribut l'artiste au commentaire
                $commentaire->setRepondre($artiste);

                // On attribut le message au commentaire
                $commentaire->setMessage($message);

                // On prepare la base de donnée 
                $entityManager->persist($commentaire);
                
                // On execute la requête
                $entityManager->flush();

                // On redirige l'utilisateur vers la page de l'artiste en prenant en parmètre l'id de l'artiste
                return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);

            }
        }

        // On retourne la vue de la page de l'artiste
        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]); 
        
    }

    // Fonction pour effacer un commentaire
    // L'artiste peut supprimer un commentaire 
    // L'utilisateur ayant crée le commentaire peut supprimer le commentaire 
    // L'administrateur peut supprimer le commentaire
    // #[Route('/user/{userId}/commentaire/{commentaireId}/delete', name: 'delete_commentaire')]
    // public function delete(
    //     $userId,
    //     $commentaireId,
    //     UserRepository $userRepository,
    //     EntityManagerInterface $entityManager,
    //     CommentaireRepository $commentaireRepository, 
    // ): Response
    // {
        
    //     $this->denyAccessUnlessGranted('ROLE_ARTISTE');

    //     // user récupère l'utilisateur ayant émis le commentaire
    //     // $user = $userRepository->find($userId);

    //     // Récupère le commentaire par l'intermédiaire de son id
    //     $commentaire = $commentaireRepository->find($commentaireId);

    //     // On récupère l'id de l'artiste en passant par l'objet commentaire
    //     $artisteId = $commentaire->getRepondre()->getId();

    //     // On récupère l'utilisateur en passant par l'objet commentaire
    //     $user = $commentaire->getCommenter();

    //     // L'utilisateur efface le nom de celui qui l'a émis
    //     $user->removeCommentaire($commentaire);

    //     // On prepare la requête
    //     $entityManager->persist($user);

    //     // On execute la requête
    //     $entityManager->flush();

    //     // On redirige sur la page de l'artiste en utilisant l'id
    //     return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);
    // }

    // Fonction pour bannir un commentaire
    #[Route('/commentaire/{commentaireId}/ban', name: 'ban_commentaire')]
    public function banCommentaire(
        $commentaireId,
        EntityManagerInterface $entityManager,
        CommentaireRepository $commentaireRepository
    ): Response
    {

        // On récupère le commentaire au moyen de son id
        $commentaire = $commentaireRepository->find($commentaireId);

        // On récupère l'id de l'artiste en passant par l'objet commentaire en récupérant l'id de l'artiste qui le reçoit
        $artisteId = $commentaire->getRepondre()->getId();

        // On affecte la valeur true a setBan
        $commentaire->setBan(true);

        // On prépare la base de donnée
        $entityManager->persist($commentaire);

        // On execute la requête
        $entityManager->flush();

        // On redirige l'utilisateur sur la page artiste
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

        // On récupère le commentaire au moyen de son id
        $commentaire = $commentaireRepository->find($commentaireId);

        // On récupère l'id de l'artiste en passant par l'objet commentaire en récupérant l'id de l'artiste qui le reçoit
        $artisteId = $commentaire->getRepondre()->getId();

        // On affecte la valeur false a setBan
        $commentaire->setBan(false);

        // On prépare la base de donnée
        $entityManager->persist($commentaire);

        // On execute la requête
        $entityManager->flush();

        // On redirige l'utilisateur sur la page artiste
        return $this->redirectToRoute('artiste_page', ['artisteId' => $artisteId]);
    }


}
