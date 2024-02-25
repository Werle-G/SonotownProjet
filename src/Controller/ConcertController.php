<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Concert;
use App\Form\ConcertType;
use App\Entity\ImageConcert;
use App\Service\PictureService;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ConcertController extends AbstractController
{
    #[Route('/concert', name: 'app_concert')]
    public function index(ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->findAll();
        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    // #[IsGranted('ROLE_ARTISTE')] 
    #[Route('/concert/{id}/edit', name: 'edit_concert')]
    #[Route('/concert/new', name: 'new_concert')]
    public function new_edit(Concert $concert = null, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        if(!$concert) {
            $concert = new Concert();  
            $concert->setUser($this->getUser());
        } 

        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On va récupérer les images
            $images = $form->get('imageConcerts')->getData();

            foreach($images as $image){
                // On défnit le dossier de destination
                $folder = 'Concerts';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new ImageConcert();
                $img->setNomImage($fichier);
                $concert->addImageConcert($img);

            }

            $entityManager->persist($concert);
            $entityManager->flush();

            return $this->redirectToRoute('app_concert');
        }

        return $this->render('concert/new.html.twig', [
            'form' => $form->createView(),
            'edit' => $concert->getId(),
            'concert' => $concert,
        ]);
    }
    

    #[Route('/concert/{id}/delete', name: 'delete_concert')]
    public function delete(Concert $concert, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($concert);
        $entityManager->flush();

        return $this->redirectToRoute('app_concert');
    }

    #[Route('/concert/{id}', name: 'show_concert')]
    public function show(Concert $concert): Response
    {

        return $this->render('concert/show.html.twig', [
            'concert' => $concert,
        ]);
    }


}
