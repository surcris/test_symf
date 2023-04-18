<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategorieController extends AbstractController
{
    // #[Route('/categorie', name: 'app_categorie')]
    // public function index(): Response
    // {
    //     return $this->render('categorie/index.html.twig', [
    //         'controller_name' => 'CategorieController',
    //     ]);
    // }
    
    #[Route('/categorie/showAll', name: 'app_categorie_showAll')]
    public function showAllCategorie(CategorieRepository $categorieRepository): Response
    {
        $msg = "";
        $categories = $categorieRepository->findAll();
        if (!$categories) {
            $msg = "Il n'y a pas de catégorie";
        }
        return $this->render('categorie/categorieShowAll.html.twig', [
            'categories' => $categories,
            'msg' => $msg,
        ]);
    }

    #[Route('/categorie/show/{id}', name: 'app_categorie_show')]
    public function showCategorie(CategorieRepository $categorieRepository,$id): Response
    {
        $categorie = $categorieRepository->find($id);
        return $this->render('categorie/uneCategorie.html.twig', [
            'categorie' => $categorie,
            
        ]);
    }

     
    #[Route('/categorie/add', name: 'app_categorie_add')]
    public function addCategorie(EntityManagerInterface $em,Request $request,CategorieRepository $repo): Response
    {
        $msg = "";
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted($categorie)) {
            $recup = $repo->findOneBy(['nom'=>$categorie->getNom()]);
            //dd($categorie,$recup);
            if (!$recup ) {
                $em->persist($categorie);
                $em->flush();
                $msg = 'La catégorie a été ajouter';
            }else{
                $msg = 'La catégorie '.$categorie->getNom().' existe déjà';
            }
           
            
        }
        
        return $this->render('categorie/categorieAdd.html.twig', [
            'form' => $form->createView(),
            'msg' => $msg,
        ]);
    }

    #[Route('/categorie/update/{id}', name: 'app_categorie_update')]
    public function updateCategorie(EntityManagerInterface $em,Request $request,CategorieRepository $repo,$id): Response
    {
        $msg = "";
        $categorie = $repo->find($id);
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted($categorie) and $form->isValid()) {
            $em->persist($categorie);
            $em->flush();
            $msg = 'La catégorie a été modifier';
            //dd($categorie,$recup);
            
        }
        
        return $this->render('categorie/categorieAdd.html.twig', [
            'form' => $form->createView(),
            'msg' => $msg,
        ]);
    }

    
    #[Route('/categorie/delete/{id}', name: 'app_categorie_delete')]
    public function deleteCategorie(EntityManagerInterface $em,CategorieRepository $repo,$id)
    {
        $msg = "";
        $categorie = $repo->find($id);
        //dd($categorie);
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('app_categorie_showAll');
    }
}
