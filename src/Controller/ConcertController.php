<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Concert;
use App\Form\ConcertType;
use App\Entity\ImageConcert;
use App\Service\PictureService;
use App\Repository\UserRepository;
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

    // Ajouter ou éditer un concert
    #[Route('/artiste/concert/{id}/edit', name: 'edit_concert')]
    #[Route('/artiste/concert/new', name: 'new_concert')]
    public function newEditConcert(Concert $concert = null, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {
        if (!$concert) {
            $concert = new Concert();  
            $concert->setUser($this->getUser());
        } 

        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les images
            $images = $form->get('imageConcerts')->getData();

            foreach ($images as $image) {
                // Définir le dossier de destination
                $folder = 'Concerts';
                // Appeler le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new ImageConcert();
                $img->setNomImage($fichier);
                $concert->addImageConcert($img);
            }

            $entityManager->persist($concert);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('artiste_page/concert/new_edit_concert.html.twig', [
            'form' => $form->createView(),
            'edit' => $concert->getId(),
            'concert' => $concert,
        ]);
    }
    
    // Supprimer un concert
    #[Route('/concert/{id}/delete', name: 'delete_concert')]
    public function deleteConcert(Concert $concert, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($concert);
        $entityManager->flush();

        return $this->redirectToRoute('app_concert');
    }

    // Tous les concerts de l'artiste
    #[Route('/artiste/concert/{id}', name: 'all_concert_per_artiste')]
    public function showConcertsByArtist($id, ConcertRepository $concertRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $concerts = $concertRepository->findBy(["user" => $user]);
        
        return $this->render('artiste_page/concert/all_concert_per_artiste.html.twig', [
            'user' => $user,
            'concerts' => $concerts,
        ]);
    }

    // Détails d'un concert de l'artiste
    #[Route('/artiste/detail/concert/{idConcert}', name: 'detail_concert')]
    public function detailConcert($idConcert, ConcertRepository $concertRepository): Response
    {
        $concert = $concertRepository->findOneBy(['id' => $idConcert]);

        $user = $concert->getUser();

        
        return $this->render('artiste_page/concert/detail_concert.html.twig', [
            'concert' => $concert,
            'user' => $user,
        ]);
    }
}
// #[Route('/artiste/{idArtiste}/concert/{idConcert}/edit', name: 'edit_concert')]
// #[Route('/artiste/{idArtiste}/concert/new', name: 'new_concert')]
// public function newEditConcert(Concert $concert = null, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService, $idArtiste): Response
// {
//     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

//     if (!$concert) {
//         $concert = new Concert();  
//         $user = $this->getUser();
//     } 

//     $form = $this->createForm(ConcertType::class, $concert);

//     $form->handleRequest($request);

//     if ($form->isSubmitted() && $form->isValid()) {
//         // Récupérer les images
//         $images = $form->get('imageConcerts')->getData();

//         foreach ($images as $image) {
//             // Définir le dossier de destination
//             $folder = 'Concerts';
//             // Appeler le service d'ajout
//             $fichier = $pictureService->add($image, $folder, 300, 300);

//             $img = new ImageConcert();
//             $img->setNomImage($fichier);
//             $concert->addImageConcert($img);
//         }

//         $entityManager->persist($concert);
//         $entityManager->flush();

//         return $this->redirectToRoute('all_concert_per_artiste', ['idArtiste' => $idArtiste, 'idConcert' => $idConcert]);
//     }

//     return $this->render('artiste_page/concert/new_edit_concert.html.twig', [
//         'form' => $form->createView(),
//         'edit' => $concert->getId(),
//         'concert' => $concert,
//     ]);
// }