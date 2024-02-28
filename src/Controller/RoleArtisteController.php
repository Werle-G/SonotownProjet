<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Cocur\Slugify\Slugify;
use App\Form\RoleArtisteType;
use Doctrine\DBAL\Types\Types;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/artiste')]
class RoleArtisteController extends AbstractController
{

    // Tout les artistes
    #[Route('/', name: 'all_artiste')]
    public function index(UserRepository $userRepository): Response
    {

        $users = $userRepository->findAllUser('["ROLE_ARTISTE"]');

        return $this->render('artiste_page/all_artiste.html.twig', [
            'users' => $users,
        ]);
    }

    // Fonction pour éditer le profil d'un artiste
    #[Route('/page/edit/{id}', name: 'artiste_page_edit')]
    public function artiste_page_edit($id, Request $request,UserRepository $userRepository, #[Autowire('%photo_dir%')]string $photoDir, EntityManagerInterface $entityManager): Response
    {

        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $userSession = $this->getUser(); 

        $userbdd = $userRepository->findOneBy(['id' => $id]);

        if($userSession == $userbdd) {

            $form = $this->createForm(RoleArtisteType::class, $userSession);
        
            $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
    
                $user = $form->getData();

                $fileName = '';

                if($photo = $form['avatar']->getData()){
                    $fileName = uniqid().'.'.$photo->guessExtension();
                    $photo->move($photoDir, $fileName);
                }
        
                $user->setAvatar($fileName);

                if($photo = $form['couverture']->getData()){
                    $fileName = uniqid().'.'.$photo->guessExtension();
                    $photo->move($photoDir, $fileName);
                }

                $user->setImageCouverture($fileName);

    
                $slugify = new Slugify();
                $slug = $slugify->slugify($user->getNomArtiste());
                $user->setSlug($slug);
                
                $entityManager->persist($user);
                $entityManager->flush();
    
                return $this->redirectToRoute('artiste_page', ['id' => $userSession->getId()]);
            }
        }
    
        return $this->render('artiste_page/page/artiste_page_edit.html.twig', [
            'form' => $form->createView(),
            'edit' => $id,
        ]);
    }



    #[Route('/page/{id}', name: 'artiste_page')]
    public function artiste_page(UserRepository $userRepository, $id): Response
    {

        $user = $userRepository->findOneBy(["id" => $id]);

        return $this->render('artiste_page/page/artiste_page.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/page', name: 'artiste_site')]
    public function artiste_site(): Response
    {
        // Si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        $userSession = $this->getUser();
    
        return $this->render('artiste_page/page/artiste_page.html.twig', [
            'user' => $userSession ,
        ]);
    }

}
