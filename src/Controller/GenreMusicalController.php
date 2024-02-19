<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\GenreMusical;
use App\Form\GenreMusicalType;
use App\Repository\AlbumRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GenreMusicalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GenreMusicalController extends AbstractController
{
    #[Route('/genre/musical', name: 'app_genre_musical')]
    public function index(GenreMusicalRepository $genreMusicalRepository): Response
    {

        $genreMusicals = $genreMusicalRepository->findAll();
        return $this->render('genre_musical/index.html.twig', [
            'genreMusicals' => $genreMusicals,
        ]);
    }

    #[Route('/genre/musical/new', name: 'new_genre_musical')]
    #[Route('/genre/musical/{id}/edit', name: 'edit_genre_musical')]
    public function new_edit(GenreMusical $genreMusical = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$genreMusical) {
            $genreMusical = new GenreMusical();  
        } 

        $form = $this->createForm(GenreMusicalType::class, $genreMusical);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genreMusical = $form->getData();
            $entityManager->persist($genreMusical);
            $entityManager->flush();

            return $this->redirectToRoute('app_genre_musical');
        }

        return $this->render('genre_musical/new.html.twig', [
            'formAddGenreMusical' => $form->createView(),
            'edit' => $genreMusical->getId()
        ]);
    }

    #[Route('/genre/musical/{id}/delete', name: 'delete_genre_musical')]
    public function delete(GenreMusical $genreMusical, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($genreMusical);
        $entityManager->flush();

        return $this->redirectToRoute('app_genre_musical'); 
    }

    #[Route('/genre/musical/{id}', name: 'show_genre_musical')]
    public function show(User $users, AlbumRepository $albumRepository, GenreMusical $genreMusical, $id)
    {

        // $artistes = $userRepository->findBy(["nomGenreMusical" => $id]);
        $albums = $albumRepository->findBy(["genreMusical" => $id]);

        return $this->render('genre_musical/show.html.twig', [
            'genreMusical' => $genreMusical,
            'albums' => $albums,
            'users' => $users,
        ]);
    }
}
