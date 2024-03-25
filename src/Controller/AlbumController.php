<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Piste;
use App\Form\AlbumType;
use App\Service\AudioService;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AlbumController extends AbstractController
{

    // Methode qui renvoie la vue de tout les albums.
    #[Route('/album', name: 'app_album')]
    public function index(AlbumRepository $albumRepository): Response
    {
        // Albums stocke les données de AlbumRepository
        // Ces données sont récupérées via la méthode findAll() de la classe AlbumRepository
        $albums = $albumRepository->findAll();

        // Renvoie la vue de index.html.twig contenu dans le dossier album
        // En paramètre la variable $albums est utilisés pour pouvoir afficher les alubms dans la vue
        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    #[Route('/album/new', name: 'new_album')]
    public function new(
        Album $album = null, 
        AudioService $audioService,
        PictureService $pictureService,
        EntityManagerInterface $entityManager, 
        Request $request, 
    ): Response
    {

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
        // Un nouvel object Album est crée
        $album = new Album();

        // L'utilisateur est récupéré et récupéré dans l'objet album
        $userSession = $this->getUser(); 

        $album->setUser($userSession);

        $slug = $album->getUser()->getSlug();

        // form stocke le formulaire crée en appellant la méthode createForm, cette méthode prend en argument la classe
        // AlbumType ainsi que l'objet Album
        $form = $this->createForm(AlbumType::class, $album);

        // Form récupère les données de la requète en prenant en argument l'objet requete
        $form->handleRequest($request);

        // Si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On stocke les données recupérées dans la variable $album
            $album = $form->getData();

            $imageAlbum = $form['imageAlbum']->getData();

            if($imageAlbum){

                $folder = 'imageAlbum';

                // On appelle le service d'ajout de la classe PictureService
                $fileName = $pictureService->add($imageAlbum, $folder, 300, 300);

                $album->setImageAlbum($fileName);
            }


            $audios = $form['pistes']->getData();

            if($audios){
                
                foreach ($audios as $index => $piste) {
                
                    $audioData = $form['pistes'][$index]['audio']->getData();
    
                    $folder = 'audios';

                    $fichier = $audioService->add($audioData, $folder);
    
                    $son = new Piste();
    
                    $album->getPistes($son = $piste->setAudio($fichier));
    
                    $album->addPiste($son);
    
                }
            }
            
            // On persiste l'album
            $entityManager->persist($album);

            // On execute la requete en base de donnée après avoir préparé la requète via la méthode persist 
            $entityManager->flush();


            $this->addFlash('success', 'Album ajouté');
            // Redirige vers la vue 'artiste_detail_album' avec en paramètre l'id de l'album récupéré avec la méthode getId de
            // l'object album
            return $this->redirectToRoute('show_album', ['slug' => $slug, 'albumId' => $album->getId()]);

        }

        // Retourne la vue de formulaire. Prend en argument la vue, la variable form du formulaire, et la variable edit pour la fo
        // fonction édition 
        return $this->render('artiste_page/album/new.html.twig', [

            // createView : méthode de la classe formInterface qui crée la vue du formulaire
            'form' => $form->createView(),
            // Récupère l'Id de l'album via la méthode getId de la classe Album
            'albumId' => $album->getId(),

        ]);
    }

    // Méthode qui permet d'éditer un album
    #[Route('/album/edit/{albumId}', name: 'edit_album')]
    public function edit(
        $albumId, 
        Album $album = null,
        Request $request,
        AlbumRepository $albumRepository, 
        EntityManagerInterface $entityManager,
        AudioService $audioService, 
        PictureService $pictureService
    ): Response
    {

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');

        $album = $albumRepository->find($albumId);

        $slug =  $album->getUser()->getSlug();

        // form stocke le formulaire crée en appellant la méthode createForm, cette méthode prend en argument la classe
        // AlbumType ainsi que l'objet Album
        $form = $this->createForm(AlbumType::class, $album);
        
        // Form récupère les données de la requète en prenant en argument l'objet requete
        $form->handleRequest($request);

        
        // Si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // On stocke les données recupérées dans la variable $album
            $album = $form->getData();
            
            // Si dans le tableau $form la propriété 'avatar' existe. On stocke la photo dans la variable $photo
            // et on rentre dans la condition
            $imageAlbum = $form['imageAlbum']->getData();

            $imageBdd = $album->getImageAlbum();
            
            if($imageAlbum){
                
                $pictureService->delete($imageBdd, 'imageAlbum', 300, 300);
                
                $folder = 'imageAlbum';
                
                // On appelle le service d'ajout de la classe PictureService
                $fileName = $pictureService->add($imageAlbum, $folder, 300, 300);

                $album->setImageAlbum($fileName);
            }

            $audios = $form['pistes']->getData();

            if($audios){

                foreach ($audios as $index => $piste) {

                    $audioData = $form['pistes'][$index]['audio']->getData();

                    $folder = 'audios';
                    
                    if($audioData){
                        
                        $fichier = $audioService->add($audioData, $folder);

                        $son = new Piste();
            
                        $album->getPistes($son = $piste->setAudio($fichier));
                        $album->addPiste($son);
                        
                    }
                }
            }

            // On persiste l'album
            $entityManager->persist($album);

            // On execute la requete en base de donnée après avoir préparé la requète via la méthode persist 
            $entityManager->flush();

            $this->addFlash('success', 'Album modifié');
            // Redirige vers la vue 'artiste_detail_album' avec en paramètre l'id de l'album récupéré avec la méthode getId de
            // l'object album
            return $this->redirectToRoute('show_album', ['slug' => $slug, 'albumId' => $album->getId()]);

        }

        // Retourne la vue de formulaire. Prend en argument la vue, la variable form du formulaire, et la variable edit pour la fo
        // fonction édition 
        return $this->render('artiste_page/album/edit.html.twig', [

            // createView : méthode de la classe formInterface qui crée la vue du formulaire
            'form' => $form->createView(),
            // edit : variable qui affiche l'album à éditer.
            // Récupère l'Id de l'album via la méthode getId de la classe Album
            'albumId' => $album->getId(),
            'album' => $album

        ]);
    }

    #[Route('/album/{albumId}/delete/piste', name: 'delete_piste', methods: ['DELETE'])]
    public function deletePiste($albumId, Request $request, AlbumRepository $albumRepository, EntityManagerInterface $entityManager, AudioService $audioService): JsonResponse
    {
        $nom = $request->query->get('nom');

        $album = $albumRepository->find($albumId);
        
        $pistes = $album->getPistes();

        $data = json_decode($request->getContent(), true);


        // if($this->isCsrfTokenValid('delete' . $imageConcert->getId(), $data['_token'])){
        //     // Le token csrf valide
        //     // On récupère le nom de l'image
        //     $nom = $imageConcert->getNomImage();

        //     if($pictureService->delete($nom, 'Concerts', 300, 300)){
        //         // On supprime l'image de la base de donnée
        //         $entityManager->remove($imageConcert);
        //         $entityManager->flush();

        //         return new JsonResponse(['success' => true], 200);
        //     }


        foreach($pistes as $piste){

            if($piste->getAudio() == $nom){

                $audioService->delete($nom, 'audios');

                $piste->setAudio('');

                $entityManager->persist($piste);

                $entityManager->flush();

                    // On supprime l'audio de la base de donnée
                    return new JsonResponse(['success' => true], 200);

                }else{
                    // La suppression a échoué
                    return new JsonResponse(['error' => 'Erreur de suppression'], 400);
                }
        }
    }

    // Fonction pour supprimer un album
    #[Route('/album/delete/{albumId}', name: 'delete_album')]
    public function delete(
        $albumId, 
        AlbumRepository $albumRepository,
        EntityManagerInterface $entityManager, 
        AudioService $audioService
    ): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // L'objet $album sera supprimé
        $album = $albumRepository->find($albumId);

        $slug =  $album->getUser()->getSlug();


        foreach($album->getPistes() as $piste){

            $audio = $piste->getAudio();

            $audioService->delete($audio, 'audios');

        }

        $entityManager->remove($album);

        // Flush execute la requete
        $entityManager->flush();

        // Redirection vers la page app_album après effacement de la bdd 
        return $this->redirectToRoute('albums_per_artiste', ['slug' => $slug]);
    }

    // Méthode qui affiche tout les albums qu'un artiste
    #[Route('/artiste/{slug}/albums', name: 'albums_per_artiste')]
    public function albumsPerArtiste(
        $slug,
        AlbumRepository $albumRepository, 
        UserRepository $userRepository, 
    ): Response
    {

        // On récupère l'utilisateur en appellant la méthode find de UserRepository
        // Cette méthode prend en argument l'id de la route mise en argument de la fonction allAlbumPerArtiste
        $user = $userRepository->findOneBy(['slug' => $slug]);

        $albums = $albumRepository->findBy(["user" => $user]);

        // Renvoie la vue des albums par artiste
        return $this->render('artiste_page/album/albums_per_artiste.html.twig', [

            // Prend un tableau de paramètre contenant les variables $albums et $user récupérés 
            'albums' => $albums,
            'user' => $user,
        ]);
    }
    
    // Détails d'un album d'un artiste
    #[Route('/artiste/{slug}/album/{albumId}', name: 'show_album')]
    public function show(
        $slug, 
        $albumId, 
        AlbumRepository $albumRepository, 
        UserRepository $userRepository, 
    ): Response
    {

        $album = $albumRepository->find($albumId);

        $user = $userRepository->findOneBy(['slug' => $slug]);

        return $this->render('artiste_page/album/show.html.twig', [
            'album' => $album,
            'user' => $user,
        ]);
    }
}
 