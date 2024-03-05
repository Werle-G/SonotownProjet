<?php

namespace App\Controller;

use App\Entity\Reseau;
use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\ReseauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteController extends AbstractController
{
    #[Route('/site', name: 'app_site')]
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'SiteController',
        ]);
    }
    #[Route('/site/edit/{id}', name:'edit_site')]
    #[Route('/site/new', name:'new_site')]
    public function newEditSite(Site $site = null, ReseauRepository $reseauRepository, EntityManagerInterface $entityManager){

        // Si l'utilisateur à le role artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');
        
        // Si le site n'existe pas, on crée un nouveau site
        // On récupère l'utilisateur
        if(!$site){
            $site = new Site();
            $site->setUser($this->getUser());
        }

        // On crée un formulaire pour récupérer le site
        $form = $this->createForm(SiteType::class, $site);


        dd($form);
        
        // Si le formulaire est soumis et est valid
        if($form->isSubmitted() && $form->isValid()){

            // On récupère les données du formulaire
            $site = $form->getData();

            // On persiste les données
            $form = $entityManager->persist($site);

            // On execute les données avec flush
            $form = $entityManager->flush();

            // On redirige l'utilisateur vers la page home
            return $this->redirectToRoute('app_home');
            
        }

        // Renvoie la vue du formulaire d'ajout de site de l'artiste
        return $this->render('artiste_page/page/_form_site.html.twig', [
            'formSite' => $form->createView(),
            'edit' => $site->getId(),
        ]);
    }

    // Pour effacer le site, on met en paramètre l'objet ainsi que l'entityManager
    #[Route('/site/{id}', name:'delete_site')]
    public function delete(Site $site, EntityManagerInterface $entityManager){

        // Si l'utilisateur à le rôle artiste
        $this->denyAccessUnlessGranted('ROLE_ARTISTE');

        // On enlève le site en utilisant la méthode remove de Entity manager
        $entityManager->remove($site);

        // On execute la requete avec la méthode flush d' Entity manager
        $entityManager->flush();

        // On redirige l'utilisateur 
        return $this->redirectToRoute('app_home');

    }
}
