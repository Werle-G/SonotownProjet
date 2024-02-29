<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Form\RoleUserType;
use Cocur\Slugify\Slugify;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class RoleUserController extends AbstractController
{

    #[Route('/profil', name: 'all_user')]
    public function index(): Response
    {

        return $this->render('profil/index.html.twig', [
        ]);
    }
    
    #[Route('/profil/edit/{id}', name: 'user_profil_edit')]
    public function user_profil_edit($id, Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager, User $user): Response
    {

        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $this->getUser(); 
        
        $userbdd = $userRepository->findOneBy(['id' => $id]);
        
        if($userSession == $userbdd) {
            
            $form = $this->createForm(RoleUserType::class, $userSession);
            
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                
                $user = $form->getData();
                
                $entityManager->persist($user);
                $entityManager->flush();
                
                return $this->redirectToRoute('user_profil');
                
            }
        }
        
        return $this->render('user_page/user_profil_edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/profil/user', name: 'user_profil')]
    public function user_profil(): Response
    {
        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $userSession = $this->getUser();
    
        return $this->render('user_page/user_profil.html.twig', [
            'user' => $userSession ,
        ]);
    }

}