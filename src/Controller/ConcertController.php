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
use Symfony\Component\HttpFoundation\JsonResponse;

class ConcertController extends AbstractController
{

    // Méthode qui permet d'afficher tout les concerts
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

    // Cette méthode ajoute un concert
    #[Route('/artiste/{slug}/concert/new', name: 'new_concert')]
    public function newEditConcert(
        $slug, 
        Concert $concert = null,
        Request $request, 
        EntityManagerInterface $entityManager, 
        PictureService $pictureService
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // On instancie un nouvel objet Concert
        $concert = new Concert();  

        // On récupère l'utilisateur et on l'attribue à l'objet concert via la méthode setUser de la classe Concert
        $concert->setUser($this->getUser());

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
                    $img->setAlt($fichier);

                    $concert->addImageConcert($img);

                }

            $entityManager->persist($concert);
            
            $entityManager->flush();

            return $this->redirectToRoute('detail_concert', ["slug" => $slug, "id" => $concert->getId()]);
        }

        return $this->render('artiste_page/concert/new_edit.html.twig', [
            'form' => $form->createView(),
            'edit' => false,
        ]);
    }

    // Cette méthode édite un concert
    #[Route('/artiste/{slug}/concert/edit/{id}', name: 'edit_concert')]
    public function editConcert(
        $slug, 
        Concert $concert, 
        Request $request, 
        EntityManagerInterface $entityManager, 
        PictureService $pictureService
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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
                $img->setAlt($fichier);

                $concert->addImageConcert($img);

            }

            $entityManager->persist($concert);
            
            $entityManager->flush();

            return $this->redirectToRoute('detail_concert', ["slug" => $slug, "id" => $concert->getId()]);
        }

        return $this->render('artiste_page/concert/new_edit.html.twig', [
            'form' => $form->createView(),
            'edit' => $concert->getId(),
            'concert' => $concert,
        ]);
    }

    #[Route('/delete/image/concert/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(ImageConcert $imageConcert, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): JsonResponse
    {

        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $imageConcert->getId(), $data['_token'])){
            // Le token csrf valide
            // On récupère le nom de l'image
            $nom = $imageConcert->getNomImage();

            if($pictureService->delete($nom, 'Concerts', 300, 300)){
                // On supprime l'image de la base de donnée
                $entityManager->remove($imageConcert);
                $entityManager->flush();

                return new JsonResponse(['success' => true], 200);
            }

            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);

        }

        return new JsonResponse(['error' => 'Token invalide'], 400);

    }

    // #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    // public function deleteImage(Images $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    // {
    //     // On récupère le contenu de la requête
    //     $data = json_decode($request->getContent(), true);

    //     if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
    //         // Le token csrf est valide
    //         // On récupère le nom de l'image
    //         $nom = $image->getName();

    //         if($pictureService->delete($nom, 'products', 300, 300)){
    //             // On supprime l'image de la base de données
    //             $em->remove($image);
    //             $em->flush();

    //             return new JsonResponse(['success' => true], 200);
    //         }
    //         // La suppression a échoué
    //         return new JsonResponse(['error' => 'Erreur de suppression'], 400);
    //     }

    //     return new JsonResponse(['error' => 'Token invalide'], 400);
    // }

    
    // Supprimer un concert
    #[Route('/artiste/{slug}/concert/delete/{id}', name: 'delete_concert')]
    public function deleteConcert(
        $slug, 
        Concert $concert,
        EntityManagerInterface $entityManager
    ): Response
    {

        $entityManager->remove($concert);
        $entityManager->flush();

        return $this->redirectToRoute('concerts_per_artiste', ['slug' => $slug]);
    }

    // Tous les concerts de l'artiste
    #[Route('/artiste/{slug}/concert/', name: 'concerts_per_artiste')]
    public function concertsPerArtist(
        $slug, 
        ConcertRepository $concertRepository, 
        UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->findOneBy(['slug' => $slug]);
        $concerts = $concertRepository->findBy(["user" => $user]);
        
        return $this->render('artiste_page/concert/concerts_per_artiste.html.twig', [
            'user' => $user,
            'concerts' => $concerts,
        ]);
    }

    // Détails d'un concert de l'artiste
    #[Route('/artiste/{slug}/concert/detail/{id}', name: 'detail_concert')]
    public function detailConcert(
        $slug, 
        $id, 
        ConcertRepository $concertRepository,
        UserRepository $userRepository
    ): Response
    {
        $concert = $concertRepository->find($id);

        $user = $userRepository->findOneBy(['slug' => $slug]);
        
        return $this->render('artiste_page/concert/detail.html.twig', [
            'concert' => $concert,
            'user' => $user,
        ]);
    }
}