<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Cocur\Slugify\Slugify;
use App\Form\RoleArtisteType;
use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\ConcertRepository;
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
    #[Route('/page/edit/{slug}', name: 'profil_edit')]
    public function pageEdit($slug, Request $request, UserRepository $userRepository, #[Autowire('%photo_dir%')]string $photoDir, EntityManagerInterface $entityManager): Response
    {
        // $this représente la classe RolArtisteController, elle refuse l'accès aux personnes non authentifiés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // On stocke l'utilisateur de la session dans la variable $userSession. On le récupère via la  méthode getUser d'AbstractController étant donné que RoleArtisteController hérite de cette classe.
        $userSession = $this->getUser(); 

        // On stocke les donnée de l'utilisateur dans la variable userBdd. La variable $userRepository récupère l'attribut id et prend l'id de l'utilisateur dans la route de la fonction artistePageEdit.
        $userBdd = $userRepository->findOneBy(['slug' => $slug]);

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

                // Si dans le tableau $form la propriété 'avatar' existe. On stocke la photo dans la variable $photo
                // et on rentre dans la condition
                if ($photo = $form['avatar']->getData()) {

                    // uniqid génère un identifiant unique 
                    // photo récupère l'extension en utilisant la méthode guessExtension de la classe File utilisées dans le formulaire RoleArtisteType
                    // fileName stocke la concatenation de uniqid et de photo
                    $fileName = uniqid().'.'.$photo->guessExtension();

                    // photo déplace déplace $fileName dans le dossier précisé dans la variable $photoDir.
                    // move prend en premier argument, le dossier de redirection et le fichier
                    $photo->move($photoDir, $fileName);

                    // $user modifie le nom du fichier contenu dans l'oject User
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

                return $this->redirectToRoute('artiste_page');
            }
        }

    
        return $this->render('artiste_page/page/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $userBdd->getId(),
            // 'sessionId' => $session->getId()
        ]);
    }

    #[Route('/', name: 'site')]
    #[Route('/{slug}', name: 'page')]
    public function artisteSitePage(UserRepository $userRepository, $slug = null): Response
    {
        
        if ($slug) {
            
            $user = $userRepository->findOneBy(["slug" => $slug]);

        } else {
            
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $user = $this->getUser();
        }

        return $this->render('artiste_page/page/show.html.twig', [
            'user' => $user,
        ]);
    }
}