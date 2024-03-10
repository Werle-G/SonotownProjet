<?php

namespace App\Controller\Administrateur;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/site', name: 'admin_')]
class SiteController extends AbstractController
{
    #[Route('/', name: 'site')]
    public function index(SiteRepository $siteRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $sites = $siteRepository->findAll();

        return $this->render('admin/site/index.html.twig', [
            'sites' => $sites,
        ]);
    }

    #[Route('/new', name: 'new_site')]
    #[Route('/{id}/edit', name: 'edit_site')]
    public function newEdit(Site $site = null, Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if(!$site) {
            $site = new Site();  
        } 

        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $site = $form->getData();
            $entityManager->persist($site);
            $entityManager->flush();

            return $this->redirectToRoute('admin_site');
        }

        return $this->render('admin/site/new.html.twig', [
            'form' => $form,
            'edit' => $site->getId()
        ]);
    }

    #[Route('/{id}/delete', name: 'delete_site')]
    public function delete(Site $site, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager->remove($site);
        $entityManager->flush();

        return $this->redirectToRoute('admin_site');
    }

    
}
