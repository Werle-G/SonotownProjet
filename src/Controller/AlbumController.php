<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Piste;
use App\Form\AlbumType;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/artiste', name: 'artiste_')]
class AlbumController extends AbstractController
{
    // Tous les albums
    #[Route('/', name: 'all_album')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    // Ajouter/Modifier des albums
    #[Route('/{id}/edit', name: 'edit_album')]
    #[Route('/new', name: 'new_album')]
    public function newEditAlbum(Album $album = null, Request $request, EntityManagerInterface $entityManager, string $photoDir): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
        if (!$album) {
            $album = new Album();
            $album->setUser($this->getUser());
            $album->addPiste(new Piste());
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $album = $form->getData();

            $photo = $form['photo']->getData();
            if ($photo) {
                $fileName = uniqid().'.'.$photo->guessExtension();
                $photo->move($photoDir, $fileName);
                $album->setImageAlbum($fileName);
            }

            foreach ($album->getPistes() as $piste) {
                $piste->setAlbum($album);
                $entityManager->persist($piste);
            }

            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('artiste_detail_album', ['idAlbum' => $album->getId()]);
        }

        return $this->render('artiste_page/album/new_edit_album.html.twig', [
            'form' => $form->createView(),
            'edit' => $album->getId(),
        ]);
    }

    // Supprimer un album
    #[Route('/{id}/delete', name: 'delete_album')]
    public function deleteAlbum(Album $album, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($album);
        $entityManager->flush();

        return $this->redirectToRoute('app_album');
    }

    // Discographie d'un artiste
    #[Route('/album/{id}', name: 'all_album_per_artiste')]
    public function allAlbumPerArtiste(AlbumRepository $albumRepository, UserRepository $userRepository, $id): Response
    {
        $user = $userRepository->find($id);
        $albums = $albumRepository->findBy(["user" => $user]);

        return $this->render('artiste_page/album/all_album_per_artiste.html.twig', [
            'albums' => $albums,
            'user' => $user,
        ]);
    }

    // Détails d'un album d'un artiste
    #[Route('/detail/{idAlbum}', name: 'detail_album')]
    public function detailAlbum($idAlbum, AlbumRepository $albumRepository): Response
    {
        $album = $albumRepository->findOneBy(['id' => $idAlbum]);

        $user = $album->getUser();

        return $this->render('artiste_page/album/detail_album.html.twig', [
            'album' => $album,
            'user' => $user,
        ]);
    }
}