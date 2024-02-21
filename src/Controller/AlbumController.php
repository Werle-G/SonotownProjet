<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Piste;
use App\Form\AlbumType;
use App\Entity\GenreMusical;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
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

    // #[IsGranted('ROLE_ARTISTE')] 
    #[Route('/album/{id}/edit', name: 'edit_album')]
    #[Route('/album/new', name: 'new_album')]
    public function new_edit(Album $album = null, Request $request, EntityManagerInterface $entityManager, #[Autowire('%photo_dir%')]string $photoDir): Response
    {

        if(!$album) {
            $album = new Album();  
            $album->setUser($this->getUser());
            $album->addPiste(new Piste()); 
        }

        $form = $this->createForm(AlbumType::class, $album);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $album = $form->getData();


            if($photo = $form['photo']->getData()){
                $fileName = uniqid().'.'.$photo->guessExtension();
                $photo->move($photoDir, $fileName);
            }
    
            $album->setImageAlbum($fileName);

            foreach ($album->getPistes() as $piste) {
                
                $piste->setAlbum($album); 
                $entityManager->persist($piste);
                $entityManager->flush();  
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

        return $this->redirectToRoute('app_album'); 
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
