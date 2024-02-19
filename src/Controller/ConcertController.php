<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Concert;
use App\Form\ConcertType;
use App\Entity\ImageConcert;
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

    #[Route('/concert/new', name: 'new_concert')]
    #[Route('/concert/{id}/edit', name: 'edit_concert')]
    public function new_edit(Concert $concert = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$concert) {
            $concert = new Concert();  
            $concert->addImageConcert(new ImageConcert());
        } 

        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $concert = $form->getData();
            $entityManager->persist($concert);
            $entityManager->flush();

            foreach ($concert->getImageConcerts() as $image) {
                $image->setConcert($concert); // Correction du nom de la méthode
                $entityManager->persist($image);
                $entityManager->flush(); // Ajout de flush() ici
            }

            return $this->redirectToRoute('app_concert');
        }

        return $this->render('concert/new.html.twig', [
            'form' => $form->createView(),
            'edit' => $concert->getId()
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
    public function show(User $user, ConcertRepository $concertRepository): Response
    {
        $concerts = $concertRepository->findBy(['user' => $user]);

        return $this->render('concert/show.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    // #[Route('/concert/{id}', name: 'show_concert')]
    // public function show(Concert $concert, $id): Response
    // {
    //     // $concerts = $concertRepository->findAll();
    //     return $this->render('concert/index.html.twig', [
    //         'concerts' => $concerts,
    //     ]);
    // }
}
