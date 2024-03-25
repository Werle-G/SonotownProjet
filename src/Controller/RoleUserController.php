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

    // #[Route('/user', name: 'all_user')]
    // public function index(): Response
    // {

    //     return $this->render('profil/index.html.twig', [
    //     ]);
    // }
    
    #[Route('/user/edit/{userId}', name: 'edit_user')]
    public function userProfilEdit(
        $userId, 
        Request $request, 
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
    ): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $this->getUser(); 
        $userBdd = $userRepository->find($userId);
        
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
                
                return $this->redirectToRoute('user_profil');
            }
        }
        
        return $this->render('user_page/profil/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $userSession,
        ]);
    }
    
    #[Route('/user/{userId}/follow/user/{userFollowId}', name: 'follow_user')]
    public function followUser(
        $userId,
        $userFollowId,
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $userRepository->find($userId);
            
        $userFollow = $userRepository->find($userFollowId);

        $user->addFollow($userFollow);

        $entityManager->persist($user);

        $entityManager->flush();
        
        return $this->redirectToRoute('user_page', ['id' => $userFollowId]);
    }

    #[Route('/user/{userId}/follow/artiste/{slug}', name: 'follow_artiste')]
    public function followArtiste(
        $userId,
        $slug, 
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


    #[Route('/user/{userId}/unfollow/artiste/{slug}', name: 'unfollow_artiste')]
    public function unFollowArtiste(
        $userId,
        $slug, 
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

    #[Route('/user/{userId}/unfollow/user/{userFollowId}', name: 'unfollow_user')]
    public function unfollowUser(
        $userId,
        $userFollowId,
        UserRepository $userRepository, 
        EntityManagerInterface $entityManager
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $userRepository->find($userId);
            
        $userFollow = $userRepository->find($userFollowId);

        $user->removeFollow($userFollow);

        $entityManager->persist($user);

        $entityManager->flush();
        
        return $this->redirectToRoute('user_page', ['id' => $userId]);
    }

    // Fonction qui affiche le profil de l'utilisateur authentifié
    #[Route('/user', name: 'user_profil')]
    #[Route('/user/{userId}', name: 'user_page')]
    public function profil(UserRepository $userRepository, $userId = null): Response
    {

        if($userId){

            $user = $userRepository->find($userId);

        }else{

            // Vérifier si l'utilisateur est connecté
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
            $user = $this->getUser();
        }
    
        return $this->render('user_page/profil/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{userId}/albums', name: 'albums_per_user')]
    public function albumsPerUser($userId): Response
    {

        $userSession = $this->getUser();

        return $this->render('user_page/album/albums_per_user.html.twig', [
            'user' => $userSession
        ]);
    }

    #[Route('/album/like/{albumId}', name: 'like_album')]
    public function likeAlbum(
        $albumId,
        UserRepository $userRepository,
        AlbumRepository $albumRepository,
        EntityManagerInterface $entityManager
    )
    {

        $userSession = $this->getUser();

        $album = $albumRepository->find($albumId);

        $slug = $album->getUser()->getSlug();

        $user = $userRepository->find($userSession->getId());

        $user->addAimerAlbum($album);

        $entityManager->persist($user);

        $entityManager->flush();

        return $this->redirectToRoute('show_album', ['slug' => $slug, 'albumId' => $albumId]);
    }

    // Fonction qui enlève des favoris l'album liké
    #[Route('/album/unlike/{albumId}', name: 'unlike_album')]
    public function unlikeAlbum(
        $albumId,
        UserRepository $userRepository,
        AlbumRepository $albumRepository,
        EntityManagerInterface $entityManager
    )
    {

        $userSession = $this->getUser();

        $album = $albumRepository->find($albumId);

        $slug = $album->getUser()->getSlug();

        $user = $userRepository->find($userSession->getId());

        //  On supprimer l'album des favoris
        $user->removeAimerAlbum($album);

        // On prépare la base de donnée
        $entityManager->persist($user);

        // On execute la requete
        $entityManager->flush();

        return $this->redirectToRoute('show_album', ['slug' => $slug, 'albumId' => $albumId]);
    }

    #[Route('/concert/like/{concertId}', name: 'like_concert')]
    public function likeConcert(
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
    #[Route('concert/unlike/{concertId}', name: 'unlike_concert')]
    public function unlikeConcert(
        $concertId,
        UserRepository $userRepository,
        ConcertRepository $concertRepository,
        EntityManagerInterface $entityManager
    )
    {
        $userSession = $this->getUser();

        $user = $userRepository->find($userSession->getId());

        $concert = $concertRepository->find($concertId);

        $slug = $concert->getUser()->getSlug();

        $user->removeAimerConcert($concert);

        // On prépare la base de donnée
        $entityManager->persist($user);

        // On execute la requete
        $entityManager->flush();

        return $this->redirectToRoute('detail_concert', ['slug' => $slug, 'concertId' => $concertId]);
    }
}