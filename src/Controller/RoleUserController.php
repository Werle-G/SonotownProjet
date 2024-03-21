<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RoleUserType;
use App\Service\PictureService;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RoleUserController extends AbstractController
{

    #[Route('/profil', name: 'all_user')]
    public function index(): Response
    {

        return $this->render('profil/index.html.twig', [
        ]);
    }
    
    #[Route('/profil/edit/{id}', name: 'user_profil_edit')]
    public function userProfilEdit(
        $id, 
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
    ): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $this->getUser(); 
        $userBdd = $userRepository->findOneBy(['id' => $id]);
        
        if ($userSession == $userBdd) {

            $form = $this->createForm(RoleUserType::class, $userSession);
            
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {


                $user = $form->getData();

                $avatarBdd = $userSession->getAvatar();

                $avatar = $form['avatar']->getData();

                if($avatar){


                    $folder = 'avatar';
    
                    // On appelle le service d'ajout de la classe PictureService
                    // En premier argument, l'image récupérée, le dossier de destination
                    $fileName = $pictureService->add($avatar, $folder, 300, 300);

                    if($fileName != $avatarBdd){

                        $pictureService->delete($avatarBdd, 'avatar', 300, 300);
                    }
    
                    $user->setAvatar($fileName);
                }
                
                $entityManager->persist($user);
                $entityManager->flush();
                
                return $this->redirectToRoute('profil');
            }
        }
        
        return $this->render('user_page/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $userSession,
        ]);
    }

    // #[Route('/profil/user/{id}/follow/artiste/{artisteId}', name: 'profil_follow')]
    
    #[Route('profil/user/{userId}/follow/artiste/{slug}', name: 'follow_artiste')]
    public function follow(
        $slug, 
        $userId,
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $userRepository->find($userId);

        $artiste = $userRepository->findOneBy(["slug" => $slug]);

        $user->addFollow($artiste);

        $entityManager->persist($user);

        $entityManager->flush();
        
        return $this->redirectToRoute('artiste_page', ['slug' => $slug]);

    }


    #[Route('profil/user/{userId}/unfollow/artiste/{slug}', name: 'unfollow_artiste')]
    public function unFollow(
        $slug, 
        $userId,
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $userRepository->find($userId);

        $artiste = $userRepository->findOneBy(["slug" => $slug]);

        $user->removeFollow($artiste);

        $entityManager->persist($user);

        $entityManager->flush();
        
        return $this->redirectToRoute('artiste_page', ['slug' => $slug]);

    }

    // Fonction qui affiche le profil de l'utilisateur authentifié
    #[Route('/profil/user', name: 'profil')]
    public function userProfil(): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $userSession = $this->getUser();
    
        return $this->render('user_page/profil.html.twig', [
            'user' => $userSession,
        ]);
    }



    #[Route('/user/{userId}/album', name: 'user_album')]
    public function albums($userId): Response
    {

        $userSession = $this->getUser();

        return $this->render('user_page/album/index.html.twig', [
            'user' => $userSession
        ]);
    }

    #[Route('profil/user/{userId}/artiste/{slug}/album/like/{albumId}', name: 'like_album')]
    public function likeAlbum(
        $slug,
        $albumId,
        $userId,
        UserRepository $userRepository,
        AlbumRepository $albumRepository,
        EntityManagerInterface $entityManager
    )
    {

        $album = $albumRepository->find($albumId);

        $user = $userRepository->find($userId);

        $user->addAimerAlbum($album);

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $albumId]);
    }

    // Fonction qui enlève des favoris l'album liké
    #[Route('profil/user/{userId}/album/unlike/{albumId}', name: 'unlike_album')]
    public function unlikeAlbum(
        $userId,
        $albumId,
        UserRepository $userRepository,
        AlbumRepository $albumRepository,
        EntityManagerInterface $entityManager
    )
    {

        // On récupère l'objet user dans l'url
        $user = $userRepository->find($userId);

        // On récupère l'id de l'album
        $album = $albumRepository->find($albumId);

        //  On supprimer l'album des favoris
        $user->removeAimerAlbum($album);

        // On prépare la base de donnée
        $entityManager->persist($user);

        // On execute la requete
        $entityManager->flush();

        return $this->redirectToRoute('user_album', ['userId' => $userId]);
    }

    #[Route('profil/user/{userId}/artiste/{slug}/concert/like/{concertId}', name: 'like_concert')]
    public function likeConcert(
        $userId,
        $slug,
        $concertId,
        UserRepository $userRepository,
        ConcertRepository $concertRepository,
        EntityManagerInterface $entityManager
    )
    {

        $user = $userRepository->find($userId);

        $concert = $concertRepository->find($concertId);

        $user->addAimerConcert($concert);

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('detail_concert', ['slug' => $slug, 'concertId' => $concertId]);
    }

    // Fonction qui enlève des favoris l'album liké
    #[Route('profil/user/{userId}/artiste/{slug}/concert/unlike/{concertId}', name: 'unlike_concert')]
    public function unlikeConcert(
        $userId,
        $slug,
        $concertId,
        UserRepository $userRepository,
        ConcertRepository $concertRepository,
        EntityManagerInterface $entityManager
    )
    {

        $user = $userRepository->find($userId);

        $concert = $concertRepository->find($concertId);

        $user->removeAimerConcert($concert);

        // On prépare la base de donnée
        $entityManager->persist($user);

        // On execute la requete
        $entityManager->flush();

        return $this->redirectToRoute('detail_concert', ['slug' => $slug, 'concertId' => $concertId]);
    }
}