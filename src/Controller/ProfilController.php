<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/profil', name: 'app_profil')]
    public function index(UserRepository $userRepository): Response
    {

        $user = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profil/edit', name: 'edit_profil')]
    public function editProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); 
    
        $form = $this->createForm(ProfilType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_profil');

        }
    
        return $this->render('profil/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/profil', name: 'show_profil')]
    // public function show(User $user, $id)
    // {

    //     // $artistes = $userRepository->findBy(["nomGenreMusical" => $id]);
    //     // $albums = $albumRepository->findBy(["genreMusical" => $id]);


    //     return $this->render('profil/show.html.twig', [
    //         // 'genreMusical' => $genreMusical,
    //         // 'albums' => $albums,
    //         'user' => $user,
    //     ]);
    // }
}
