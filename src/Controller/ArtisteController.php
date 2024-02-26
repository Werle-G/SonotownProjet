<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\DBAL\Types\Types;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\ConcertRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/artiste', name: 'artiste_')]
class ArtisteController extends AbstractController
{

    // Tout les artistes
    #[Route('/', name: 'all_artiste')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAllUser('["ROLE_ARTISTE"]');

        return $this->render('artiste/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/profil/edit/{id}', name: 'edit_profil')]
    public function editProfil($id, Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $this->getUser(); 

        $userbdd = $userRepository->findOneBy(['id' => $id]);

        if($userSession == $userbdd) {

            $form = $this->createForm(ProfilType::class, $userSession);
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
    
                $user = $form->getData();
    
                $slugify = new Slugify();
                $slug = $slugify->slugify($user->getNomArtiste());
                $user->setSlug($slug);
                
                // $entityManager->persist($user);
                // $entityManager->flush();
    
                // return $this->redirectToRoute('show_profil');
    
            }
        }
    
        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    // Page d'un artiste
    #[Route('/{id}', name: 'page_artiste')]
    public function show(User $user, PostRepository $postRepository, $id):Response
    {

        $posts = $postRepository->findBy(["user" => $id]);

        return $this->render('artiste/page.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

}
