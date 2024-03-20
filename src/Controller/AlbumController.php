<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Album;
use App\Entity\Piste;
use App\Form\AlbumType;
use App\Service\AudioService;
use App\Service\PictureService;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Repository\PisteRepository;
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

    #[Route('/artiste/{slug}/album/new', name: 'album_new')]
    public function new(
        $slug, 
        Album $album = null, 
        Request $request, 
        AudioService $audioService,
        EntityManagerInterface $entityManager, 
        #[Autowire('%photo_dir%')]string $photoDir,  
    ): Response
    {

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
        // Un nouvel object Album est crée
        $album = new Album();

        // L'utilisateur est récupéré et récupéré dans l'objet album
        $album->setUser($this->getUser());

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
            $photo = $form['photo']->getData();

            
            // $audio = $form->get('pistes')->getData();
            // $brochureFile = $form->get('brochure')->getData();

            if ($photo) {
                // uniqid génère un identifiant unique 
                // photo récupère l'extension en utilisant la méthode guessExtension de la classe File utilisées dans le formulaire RoleArtisteType
                // fileName stocke la concatenation de uniqid et de photo
                $fileName = uniqid().'.'.$photo->guessExtension();

                // photo déplace déplace $fileName dans le dossier précisé dans la variable $photoDir.
                // move prend en premier argument, le dossier de redirection et le fichier
                $photo->move($photoDir, $fileName);

                // $album modifie le nom du fichier contenu dans l'oject album
                $album->setImageAlbum($fileName);

                // dd($album);
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

            // Redirige vers la vue 'artiste_detail_album' avec en paramètre l'id de l'album récupéré avec la méthode getId de
            // l'object album
            return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $album->getId()]);

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
    #[Route('/artiste/{slug}/album/edit/{albumId}', name: 'album_edit')]
    public function edit(
        $slug, 
        $albumId, 
        Album $album = null,
        Request $request,
        AlbumRepository $albumRepository, 
        EntityManagerInterface $entityManager,
        AudioService $audioService, 
        #[Autowire('%photo_dir%')]string $photoDir,  
    ): Response
    {

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');

        $album = $albumRepository->find($albumId);

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
            $photo = $form['photo']->getData();

            if ($photo) {

                // uniqid génère un identifiant unique 
                // photo récupère l'extension en utilisant la méthode guessExtension de la classe File utilisées dans le formulaire RoleArtisteType
                // fileName stocke la concatenation de uniqid et de photo
                $fileName = uniqid().'.'.$photo->guessExtension();

                // photo déplace déplace $fileName dans le dossier précisé dans la variable $photoDir.
                // move prend en premier argument, le dossier de redirection et le fichier
                $photo->move($photoDir, $fileName);

                // $album modifie le nom du fichier contenu dans l'oject album
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

            // Redirige vers la vue 'artiste_detail_album' avec en paramètre l'id de l'album récupéré avec la méthode getId de
            // l'object album
            return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $album->getId()]);

        }

        // Retourne la vue de formulaire. Prend en argument la vue, la variable form du formulaire, et la variable edit pour la fo
        // fonction édition 
        return $this->render('artiste_page/album/edit.html.twig', [

            // createView : méthode de la classe formInterface qui crée la vue du formulaire
            'form' => $form->createView(),
            // edit : variable qui affiche l'album à éditer.
            // Récupère l'Id de l'album via la méthode getId de la classe Album
            'albumId' => $album->getId(),

        ]);
    }

    #[Route('/album/{albumId}/delete/piste', name: 'delete_piste', methods: ['DELETE'])]
    public function deletePiste($albumId, Request $request, AlbumRepository $albumRepository, EntityManagerInterface $entityManager, AudioService $audioService): JsonResponse
    {
        $nom = $request->query->get('nom');

        $album = $albumRepository->find($albumId);
        
        $pistes = $album->getPistes();

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
    #[Route('/artiste/{slug}/album/{albumId}/delete', name: 'album_delete')]
    public function delete($slug, $albumId, AlbumRepository $albumRepository,EntityManagerInterface $entityManager, AudioService $audioService): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // L'objet $album sera supprimé
        $album = $albumRepository->find($albumId);


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
    #[Route('/artiste/{slug}/album/', name: 'albums_per_artiste')]
    public function albumsPerArtiste(
        AlbumRepository $albumRepository, 
        UserRepository $userRepository, 
        $slug
    ): Response
    {

        // On récupère l'utilisateur en appellant la méthode find de UserRepository
        // Cette méthode prend en argument l'id de la route mise en argument de la fonction allAlbumPerArtiste
        $user = $userRepository->findOneBy(['slug' => $slug]);

        $albums = $albumRepository->findBy(["user" => $user]);


        // Renvoie la vue des albums par artiste
        return $this->render('artiste_page/album/all_album_per_artiste.html.twig', [

            // Prend un tableau de paramètre contenant les variables $albums et $user récupérés 
            'albums' => $albums,
            'user' => $user,
        ]);
    }
    
    // Détails d'un album d'un artiste
    #[Route('/artiste/{slug}/album/detail/{albumId}', name: 'album_detail')]
    public function detail($slug, $albumId, AlbumRepository $albumRepository, UserRepository $userRepository): Response
    {

        // On récupère l'album via la méthode findOneBy de AlbumRepository
        // On prend en argument l'id de l'album
        $album = $albumRepository->find($albumId);

        // On stocke l'utilisateur dans $user en récupèrant l'user de l'album avec la méthode getUser de la classe Album
        $user = $userRepository->findOneBy(['slug' => $slug]);

        // Renvoie la vue du detail de l'album en utilisant la méthode rendre
        // Cette méthode prend en argument la vue et un tableau de paramètre content l'objet Album et User
        return $this->render('artiste_page/album/detail.html.twig', [
            'album' => $album,
            'user' => $user,
        ]);
    }



}