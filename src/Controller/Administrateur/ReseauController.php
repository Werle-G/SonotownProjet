<?php

namespace App\Controller\Administrateur;

use App\Entity\Reseau;
use App\Form\ReseauType;
use App\Repository\ReseauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/reseau', name: 'admin_')]
class ReseauController extends AbstractController
{
    #[Route('/', name: 'reseau')]
    public function index(ReseauRepository $reseauRepository): Response
    {
        $reseaux = $reseauRepository->findAll();

        return $this->render('admin/reseau/index.html.twig', [
            'reseaux' => $reseaux,
        ]);
    }

    #[Route('/new', name: 'new_reseau')]
    #[Route('/{id}/edit', name: 'edit_reseau')]
    public function newEdit(Reseau $reseau = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if(!$reseau) {
            $reseau = new Reseau();  
        } 

        $form = $this->createForm(ReseauType::class, $reseau);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $reseau = $form->getData();
            $entityManager->persist($reseau);
            $entityManager->flush();

            return $this->redirectToRoute('admin_reseau');
        }

        return $this->render('admin/reseau/new.html.twig', [
            'form' => $form,
            'edit' => $reseau->getId()
        ]);
    }

    #[Route('/{id}/delete', name: 'delete_reseau')]
    public function delete(Reseau $reseau, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($reseau);
        $entityManager->flush();

        return $this->redirectToRoute('admin_reseau');
    }

    
}
