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

    // Méthode qui permet d'afficher tout les concerts
    // Prend en argument la classe ConcertRepository et renvoie une réponse HTTP avec la classe Response 
    #[Route('/concert', name: 'app_concert')]
    public function index(ConcertRepository $concertRepository): Response
    {

        // On récupère dans la variable $concerts tout les concerts en appellant la méthode findAll de la classse ConcertRepository
        // La méthode findAll() récupère tout les concerts sans distinctions
        $concerts = $concertRepository->findAll();

        // Renvoie la vue en appellant la méthode render.
        // Prend un tableau contenant la varaible $concerts
        return $this->render('concert/index.html.twig', [
            'concerts' => $concerts,
        ]);
    }

    // Elle prend en argument , l'Id pour récupérer le concert
    // La classe EntityManagerInterface est la classe fille (hérite) de ObjectManager. 
    // La classe Response renvoie une réponse HTTP
    // La classe Request effectue la requete
    // Concert $concert = null : si l'Id d'un concert n'est pas récupéré, la variable est null et on crée un nouvel objet en utilisant la condition  if(!concert)
    // PictureService : classe qui permet de 

    // Cette méthode édite ou un ajoute un concert
    #[Route('/artiste/concert/{id}/edit', name: 'edit_concert')]
    #[Route('/artiste/concert/new', name: 'new_concert')]
    public function newEditConcert(Concert $concert = null, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): Response
    {

        // Si concert n'existe pas
        if (!$concert) {

            // On instancie un nouvel objet Concert
            $concert = new Concert();  

            // On récupère l'utilisateur et on l'attribue à l'objet concert via la méthode setUser de la classe Concert
            $concert->setUser($this->getUser());
        } 

        // la variable form stocke le formulaire crée en appellant la méthode createForm, cette méthode prend en argument la classe
        // ConcertType ainsi que l'objet Concert
        $form = $this->createForm(ConcertType::class, $concert);

        // Form récupère les données de la requète en prenant en argument l'objet requete
        $form->handleRequest($request);

        // Si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les images du formulaire en récupérant d'abord les données du formulaire
            // et on stocke les images dans la variable $images
            $images = $form->get('imageConcerts')->getData();

            // On parcourt le tableau d'images
            foreach ($images as $image) {

                // On définit le dossier de destination
                $folder = 'Concerts';

                // On appelle le service d'ajout de la classe PictureService
                // En premier argument, l'image récupérée, le dossier de 
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
    #[Route('/artiste/{slug}/concert/', name: 'concerts_per_artiste')]
    public function concertsByArtist(
        $slug, 
        ConcertRepository $concertRepository, 
        UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->findonBy($slug);
        $concerts = $concertRepository->findBy(["user" => $user]);
        
        return $this->render('artiste_page/concert/concerts_per_artiste.html.twig', [
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