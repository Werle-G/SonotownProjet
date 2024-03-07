<?php

namespace App\Controller;

use App\Entity\Piste;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PisteController extends AbstractController
{
    #[Route('/piste', name: 'app_piste')]
    public function index(): Response
    {
        return $this->render('piste/index.html.twig', [
            'controller_name' => 'PisteController',
        ]);
    }

    // #[Route('/{id}/edit', name: 'edit_piste')]
    // #[Route('/new', name: 'new_piste')]
    // public function newEdit(Piste $piste = null, Request $request, EntityManagerInterface $entityManager, #[Autowire('%audio_dir%')]string $audioDir): Response
    // {

    //     // Si l'utilisateur à le rôle artiste
    //     $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
    //     // Si l'album n'existe pas
    //     if (!$album) {

    //         // Un nouvel object Album est crée
    //         $album = new Album();

    //         // L'utilisateur est récupéré et récupéré dans l'objet album
    //         $album->setUser($this->getUser());

    //         // La méthode addPiste ajoute un nouvel objet Piste 

    //     }

    //     // form stocke le formulaire crée en appellant la méthode createForm, cette méthode prend en argument la classe
    //     // AlbumType ainsi que l'objet Album
    //     $form = $this->createForm(AlbumType::class, $album);

    //     // Form récupère les données de la requète en prenant en argument l'objet requete
    //     $form->handleRequest($request);

    //     // Si le formulaire a été soumis et est valide
    //     if ($form->isSubmitted() && $form->isValid()) {

    //         // On stocke les données recupérées dans la variable $album
    //         $album = $form->getData();
    //     }
    // }
}