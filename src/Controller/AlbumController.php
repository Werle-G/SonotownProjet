<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Piste;
use App\Form\AlbumType;
use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use App\Service\AudioService;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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


    // Méthode qui permet d'éditer un album
    #[Route('/artiste/{slug}/album/{albumId}/edit', name: 'album_edit')]
    public function edit(
        $slug, 
        $albumId, 
        Request $request,
        AlbumRepository $albumRepository, 
        EntityManagerInterface $entityManager,
        AudioService $audioService, 
        #[Autowire('%photo_dir%')]string $photoDir,  
        #[Autowire('%audio_dir%')]string $audioDir
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
            
            // $audio = $form->get('pistes')->getData();
            // $brochureFile = $form->get('brochure')->getData();

            if ($photo) {

                // dd($photo);
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

            // dd($form['pistes']->getData());
            // if($form['pistes']->getData()){

                $pistes = $form['pistes']->getData();
    // 
    dd($pistes);
                // dd($pistes);
                // dd($pistes);
                    foreach($pistes as $key => $pist){
                        $folder = 'audios';
                        
                        $fichier = $audioService->add($pist, $folder, 300, 300);

                        $piste = new Piste();
        
                        // $img->setNomImage($fichier);
        
                        // $concert->addImageConcert($img);
                        // dd($piste);
                        // $audio = $piste->getAudio();

                        // $test = pathinfo($audio);
                        // dd($test);
                        // $test = pathinfo($audio(['basename']));
                        
                        // $fileName = uniqid().'.'.$audio->guessExtension();
                        // dd($fileName);

                        // $audio->move($audioDir, $fileName);
        
                        // $piste = new Piste();

                        $piste->setAudio($fichier);
                        
                        // dd($fileName);

                        $album->addPiste($piste);

                        
                    }
                // }

                dd($album);
            
            // On persiste l'album
            $entityManager->persist($album);
            
            // dd($album);

            // On execute la requete en base de donnée après avoir préparé la requète via la méthode persist 
            $entityManager->flush();


            // Redirige vers la vue 'artiste_detail_album' avec en paramètre l'id de l'album récupéré avec la méthode getId de
            // l'object album
            return $this->redirectToRoute('album_detail', ['slug' => $slug, 'albumId' => $album->getId()]);

        }

        // Retourne la vue de formulaire. Prend en argument la vue, la variable form du formulaire, et la variable edit pour la fo
        // fonction édition 
        return $this->render('artiste_page/album/new_edit.html.twig', [

            // createView : méthode de la classe formInterface qui crée la vue du formulaire
            'form' => $form->createView(),
            'edit' => true,
            // edit : variable qui affiche l'album à éditer.
            // Récupère l'Id de l'album via la méthode getId de la classe Album
            'albumId' => $album->getId(),

        ]);
    }

    #[Route('/artiste/{slug}/album/new', name: 'album_new')]
    public function new(
        $slug, 
        Album $album, 
        Request $request, 
        EntityManagerInterface $entityManager, 
        #[Autowire('%photo_dir%')]string $photoDir,  
        #[Autowire('%audio_dir%')]string $audioDir
    ): Response
    {

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
        // Si l'album n'existe pas

            // Un nouvel object Album est crée
            $album = new Album();

            // L'utilisateur est récupéré et récupéré dans l'objet album
            $album->setUser($this->getUser());

            // La méthode addPiste ajoute un nouvel objet Piste 
            $album->addPiste(new Piste());


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
            $pistes = $form->get('pistes')->getData();

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


            if($pistes){

                foreach ($pistes as $piste) {
                    $fileName = uniqid().'.'.$piste->getClientOriginalName();
                    $piste->setAlbum($fileName);
                    dd($piste);

                    $album->addPiste($piste);

                    $entityManager->persist($piste);
                    $entityManager->flush();
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
        return $this->render('artiste_page/album/new_edit.html.twig', [

            // createView : méthode de la classe formInterface qui crée la vue du formulaire
            'form' => $form->createView(),
            'edit' => false,
            // Récupère l'Id de l'album via la méthode getId de la classe Album
            'albumId' => $album->getId(),

        ]);
    }

    // Fonction pour supprimer un album
    #[Route('/artiste/{slug}/album/{albumId}/delete', name: 'album_delete')]
    public function delete($slug, $albumId, AlbumRepository $albumRepository,EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // L'objet $album sera supprimé
        $album = $albumRepository->find($albumId);

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