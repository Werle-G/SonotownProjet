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

    #[Route('/user/{userId}/playlist', name: 'user_playlist')]
    public function playlists($userId, PlaylistRepository $playlistRepository, Request $request): Response
    {

        $playlists = $playlistRepository->findBy(['user' => $userId]);
        $userSession = $this->getUser();

        return $this->render('user_page/playlist/index.html.twig', [
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

}