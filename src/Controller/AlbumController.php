<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController
{
    #[Route('/album', name: 'app_album')]
    public function index(AlbumRepository $albumRepository): Response
    {
        $albums = $albumRepository->findAll();
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }



    #[Route('/album/new', name: 'new_album')]
    #[Route('/album/{id}/edit', name: 'edit_album')]
    public function new_edit(Album $album = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$album) {
          $album = new Album();  
        } 

        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
            $album = $form->getData();
            // prepare PDO
            $entityManager->persist($album);
            // execute PDO
            $entityManager->flush();

            return $this->redirectToRoute('app_album');
        }

        //  bloc soumssion

        return $this->render('album/new.html.twig', [
            'formAddAlbum' => $form,
            'edit' => $album->getId()
        ]);
    }

    #[Route('/album/{id}/delete', name: 'delete_album')]
    public function delete(Album $album, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($album);
        $entityManager->flush();

        return $this->redirectToRoute('app_album'); // Rediriger vers une autre page après la suppression
    }

    #[Route('/album/{id}', name: 'show_album')]
    public function show(Album $album, PisteRepository $pisteRepository, $id):Response
    {

        $pistes = $pisteRepository->findBy(["album" => $id]);
        
        return $this->render('album/show.html.twig', [
            'user' => $this->getUser(),
            'album' => $album,
            'pistes' => $pistes,
        ]);
    }


}
