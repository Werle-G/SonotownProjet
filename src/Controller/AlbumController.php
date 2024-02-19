<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Piste;
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

    #[Route('/album/{id}/edit', name: 'edit_album')]
    #[Route('/album/new', name: 'new_album')]
    public function new_edit(Album $album = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$album) {
            $album = new Album();  
            $album->addPiste(new Piste());  
        }
    
        $form = $this->createForm(AlbumType::class, $album);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $album = $form->getData();
    
            // Associer les pistes à l'album
            foreach ($album->getPistes() as $piste) {
                $piste->setAlbum($album); 
                $entityManager->persist($piste);
            }
    
            // Associer les genres sélectionnés à l'album
            // Supposons que votre formulaire a un champ 'genreMusicals' qui contient les genres sélectionnés
            foreach ($form->get('genreMusicals')->getData() as $genre) {
                $album->addGenreMusical($genre);
                dd($album);
            }
    
            $entityManager->persist($album);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_album');
        }
    
        return $this->render('album/new.html.twig', [
            'form' => $form->createView(),
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
