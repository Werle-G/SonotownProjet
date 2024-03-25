<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlaylistController extends AbstractController
{


    #[Route('/playlist', name: 'app_playlist')]
    public function index(): Response
    {
        return $this->render('playlist/index.html.twig', [
            'controller_name' => 'PlaylistController',
        ]);
    }

    // Fonction qui affiche toutes les playlists d'un utilisateur authentifié
    #[Route('/user/{userId}/playlists', name: 'user_playlist')]
    public function playlistsPerUser($userId, PlaylistRepository $playlistRepository): Response
    {

        $playlists = $playlistRepository->findBy(['user' => $userId]);

        $userSession = $this->getUser();

        return $this->render('user_page/playlist/playlists_per_user.html.twig', [
            'playlists' => $playlists,
            'user' => $userSession
        ]);
    }

    #[Route('/user/{userId}/new/playlist', name: 'new_playlist')]
    public function new(
        $userId, 
        Playlist $playlist = null, 
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $playlist = new playlist();

        $playlist->setUser($this->getUser());

        $form = $this->createForm(PlaylistType::class, $playlist);
            
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
        
                $playlist = $form->getData();

                $entityManager->persist($playlist);

                $entityManager->flush();

                return $this->redirectToRoute('user_playlist', ['userId' => $userId]);
            }
        
        return $this->render('user_page/playlist/new.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId,
            'edit' => false
        ]);
    }

    #[Route('profil/user/{userId}/piste/{pisteId}', name: 'new_piste_playlist')]
    public function newPlaylistPiste(
        $userId,
        $pisteId,
        Playlist $playlist = null, 
        PisteRepository $pisteRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $userRepository->findOneById($userId);
        
        $piste = $pisteRepository->find($pisteId);
        
        $slug =  $piste->getAlbum()->getUser()->getSlug();
        $albumId = $piste->getAlbum()->getId();
        
        $titrePlaylist = $request->get('titrePlaylist');

            if($userSession && $piste && $titrePlaylist){

                $playlist = new playlist();
                
                $playlist->setUser($userSession);
                $playlist->setNomPlaylist($titrePlaylist);
                $playlist->addAjouter($piste);

                $entityManager->persist($playlist);
                $entityManager->flush();

                return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $albumId]);

            }

        return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $albumId]); 
        
    }

    #[Route('/user/{userId}/edit/playlist/{playlistId}', name: 'edit_playlist')]
    public function edit(
        $userId, 
        $playlistId,
        PlaylistRepository $playlistRepository, 
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $playlist = $playlistRepository->find($playlistId);

        $form = $this->createForm(PlaylistType::class, $playlist);
            
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
        
                $playlist = $form->getData();

                $entityManager->persist($playlist);

                $entityManager->flush();

                return $this->redirectToRoute('user_playlist', ['userId' => $userId]);
            }
        
        return $this->render('user_page/playlist/new.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId,
            'edit' => $playlist
        ]);
    }

    #[Route('artiste/{slug}/album/{albumId}/playlist/{playlistId}/add/piste/{id}', name: 'add_piste_playlist')]
    public function addPiste(
        $id, 
        $slug,
        $albumId,
        $playlistId, 
        PisteRepository $pisteRepository, 
        PlaylistRepository $playlistRepository, 
        EntityManagerInterface $entityManager
    )
    {

        $playlist = $playlistRepository->find($playlistId);

        $piste = $pisteRepository->find($id);

        $playlist->addAjouter($piste);

        $entityManager->flush();

        return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $albumId]);
    }



    #[Route('artiste/{slug}/album/{albumId}/playlist/{playlistId}/add/piste/{id}', name: 'add_piste_playlist')]
    public function removePiste(
        $id, 
        $slug,
        $albumId,
        $playlistId, 
        PisteRepository $pisteRepository, 
        PlaylistRepository $playlistRepository, 
        EntityManagerInterface $entityManager
    )
    {

        $playlist = $playlistRepository->find($playlistId);

        $piste = $pisteRepository->find($id);

        $playlist->removeAjouter($piste);

        // On prépare la base de donnée
        $entityManager->persist($playlist);

        $entityManager->flush();

        return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $albumId]);
    }
    

    #[Route('user/{userId}/delete/playlist/{id}', name: 'delete_playlist')]
    public function delete($userId, Playlist $playlist, EntityManagerInterface $entityManager): Response
    {
        
        $entityManager->remove($playlist);
        $entityManager->flush();

        return $this->redirectToRoute('user_playlist', ['userId' => $userId]);
    }


    #[Route('/user/{userId}/playlist/{playlistId}', name: 'show_playlist')]
    public function show($userId, $playlistId, PlaylistRepository $playlistRepository): Response
    {

        $playlist = $playlistRepository->find($playlistId);

        $userSession = $this->getUser();

        return $this->render('user_page/playlist/show.html.twig', [
            'playlist' => $playlist,
            'user' => $userSession,
        ]);
    }

}