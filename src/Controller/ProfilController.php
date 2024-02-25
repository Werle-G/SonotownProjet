<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfilController extends AbstractController
{

    #[Route('/profil/visiteur', name: 'app_profil')]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('profil/index.html.twig', [
            // 'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profil/edit/', name: 'edit_profil', methods: ['GET', 'POST'])]
    public function editProfil(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {

        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser(); 
    
        $form = $this->createForm(ProfilType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            // $password = $user->get('plainPassword');

            // $hash = $password;
            

            // if(password_verify($password,$hash)){


            $npassword = $form->getData();
            $post = $form->getData();

            
            $this->addFlash('succes',
                'Votre compte a été modifié');

            return $this->redirectToRoute('show_profil');

        }
    
        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

        

    #[IsGranted('ROLE_USER')]
    #[Route('/profil', name: 'show_profil')]
    public function show(): Response
    {

        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        return $this->render('profil/show.html.twig', [
            'user' => $user,
        ]);
    }
}
