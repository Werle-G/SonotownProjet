<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Cocur\Slugify\Slugify;
use App\Form\RoleArtisteType;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use App\Repository\ConcertRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/artiste', name: 'artiste_')]
class RoleArtisteController extends AbstractController
{

    // La méthode index affiche les utilisateurs selon le rôle artiste. 
    // #[Route('/', name: 'all_artiste')]
    public function index(UserRepository $userRepository): Response
    {

        // La variable $users récupère tout les utilisateurs ayant le rôle Artiste en utilisant la 
        // méthode findUserByRole de la classe UserRepository.
        // Cette méthode prend en argument la propriété $roles.
        $users = $userRepository->findUserByRole('["ROLE_ARTISTE"]');

        // return renvoie la vue 
        return $this->render('artiste_page/all_artiste.html.twig', [
            'users' => $users,
        ]);
    }

    // Cette méthode édite la page artiste
    #[Route('/edit/{slug}', name: 'edit')]
    public function edit(
        $slug, 
        UserRepository $userRepository, 
        PictureService $pictureService, 
        EntityManagerInterface $entityManager,
        Request $request
    ): Response
    {
        // $this représente la classe RolArtisteController, elle refuse l'accès aux personnes non authentifiés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // On stocke l'utilisateur de la session dans la variable $userSession. On le récupère via la  méthode getUser d'AbstractController étant donné que RoleArtisteController hérite de cette classe.
        $userSession = $this->getUser(); 
        
        // On stocke les donnée de l'utilisateur dans la variable userBdd. La variable $userRepository récupère l'attribut id et prend l'id de l'utilisateur dans la route de la fonction artistePageEdit.
        $userBdd = $userRepository->findOneBy(["slug" => $slug]);

        $slugBdd = $userBdd->getSlug(); 

        // Si l'utilisateur de la session est identique à l'utilisateur récupéré dans la base de donnée
        if ($userSession == $userBdd) {


            // Un formulaire est crée via la méthode createForm (méthode de la classe AbstractController)
            // Cette méthode prend en argument le formulaire de RoleArtisteType, et les données de la session de l'utilisateur
            $form = $this->createForm(RoleArtisteType::class, $userSession);
        
            // La variable form récupère les données de la requète en prenant en argument la requète
            $form->handleRequest($request);

            // Si le formulaire a été soumis est est valide
            if ($form->isSubmitted() && $form->isValid()) {

                // On stocke les données recupérées dans la variable $user
                $user = $form->getData();

                $avatar = $form['avatar']->getData();

                $avatarBdd = $userSession->getAvatar();


            
                if($avatar){

                    $folder = 'avatar';
                    
                    // On appelle le service d'ajout de la classe PictureService
                    // En premier argument, l'image récupérée, le dossier de 
                    $fileName = $pictureService->add($avatar, $folder, 300, 300);
    
                    if($fileName != $avatarBdd){
    
                        $pictureService->delete($avatarBdd, 'avatar', 300, 300);
                    }
    
                    $user->setAvatar($fileName);
                }

                // Un object Slugify est nouvellement crée et stocké dans la variableslugify
                $slugify = new Slugify();

                // slug stocke le nom de l'artiste récupéré via l'oject $slugify
                // l'object slugify appelle la méthode slugify de sa classe, cette méthode transforme une chaine de charactère en enlevant les espaces, les majuscules et les charactères spéciaux. Elle est utilisée pour rendre les URL plus lisibles pour les moteurs de recherches. En argument de cette méthode, le nom de l'artiste récupéré via la méthode getNomArtiste de la classe User.
                $slug = $slugify->slugify($user->getNomArtiste());

                // slug est ajouté à l'objet User
                $user->setSlug($slug);
                
                // EntityManager appelle la méthode persist et persist l'utilisateur. 
                // On persiste pour préparer la base de donnée.
                
                $entityManager->persist($user);

                // EntityManager appelle la méthode flush pour executer la requête.
                $entityManager->flush();

                // L'utilisateur est redirigé vers sa page artiste.
                // La méthode redirectToRoute prend en argument la route à atteindre ainsi que l'Id indiqué dans la route pour renvoyer 
                // l'utilisateur vers sa page.

                return $this->redirectToRoute('artiste_site');
            }
        }

        return $this->render('artiste_page/page/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $userSession,
        ]);
    }

    #[Route('/', name: 'site')]
    public function artisteSite(
        CommentaireRepository $commentaireRepository
    ): Response
    {
            
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $userId = $user->getId();

        $commentaires = $commentaireRepository->findCommentaireByArtiste($userId);

        return $this->render('artiste_page/page/show.html.twig', [
            'user' => $user,
            'commentaires' => $commentaires
        ]);
    }

    // #[Route('/{slug}', name: 'page')]
    // public function artistePage(
    //     $slug,
    //     UserRepository $userRepository,
    //     CommentaireRepository $commentaireRepository
    // ): Response
    // {
        
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     $commentaires = $commentaireRepository->findOneBy(['slug' => $slug]);
        
    //     $user = $userRepository->findOneBy(["slug" => $slug]);
            
    //     return $this->render('artiste_page/page/show.html.twig', [
    //         'user' => $user,
    //         'commentaires' => $commentaires
    //     ]);
    // }
    
    #[Route('/{artisteId}', name: 'page')]
    public function artistePage(
        $artisteId,
        UserRepository $userRepository,
        CommentaireRepository $commentaireRepository
    ): Response
    {
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $commentaires = $commentaireRepository->findCommentaireByArtiste($artisteId);

        $user = $userRepository->find($artisteId);

        return $this->render('artiste_page/page/show.html.twig', [
            'user' => $user,
            'commentaires' => $commentaires
        ]);
    }
}