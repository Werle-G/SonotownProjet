<?php

namespace App\Controller\Administrateur;

use App\Entity\GenreMusical;
use App\Form\GenreMusicalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GenreMusicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/genre/musical', name: 'admin_')]

class GenreMusicalController extends AbstractController
{
    #[Route('/', name: 'genre_musical')]
    public function index(GenreMusicalRepository $genreMusicalRepository): Response
    {

        $genreMusicals = $genreMusicalRepository->findAll();

        return $this->render('admin/genre_musical/index.html.twig', [
            'genreMusicals' => $genreMusicals,
        ]);
    }

    #[Route('/new', name: 'new_genre_musical')]
    #[Route('/{id}/edit', name: 'edit_genre_musical')]
    public function new_edit(GenreMusical $genreMusical = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if(!$genreMusical) {
            $genreMusical = new GenreMusical();  
        } 

        $form = $this->createForm(GenreMusicalType::class, $genreMusical);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $genreMusical = $form->getData();
            $entityManager->persist($genreMusical);
            $entityManager->flush();

            return $this->redirectToRoute('admin_genre_musical');
        }

        return $this->render('admin/genre_musical/new.html.twig', [
            'form' => $form,
            'edit' => $genreMusical->getId()
        ]);
    }

    #[Route('/{id}/delete', name: 'delete_genre_musical')]
    public function delete(GenreMusical $genreMusical, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($genreMusical);
        $entityManager->flush();

        return $this->redirectToRoute('admin_genre_musical');
    }
}
