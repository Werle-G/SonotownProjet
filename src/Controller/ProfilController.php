<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Cocur\Slugify\Slugify;
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
    public function index(): Response
    {

        return $this->render('profil/index.html.twig', [
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

                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('show_profil');
    
            }
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
