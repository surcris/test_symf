<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping\Id;
use App\Entity\Article;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            // 'connect' => 0,
        ]);
    }

    // #[Route('/article/calc/{val1}', name: 'calc_article')]
    // public function calc(int $val1): Response
    // {
    //     $total = 0;
    //     for ($i=1; $i <= $val1; $i++) { 
    //         if ($i < 11) {
    //             $total += 1.40;
    //         }else if($i < 21 and $i > 10){
    //             $total += 1.30;
    //         }else if($i > 20 ){
    //             $total += 1.20;
    //         }
            
    //     }
    //     return $this->render('article/index.html.twig', [
    //         // 'connect' => 0,
    //         'total' => $total,
            
    //     ]);
    // }

    #[Route('/article/all', name: 'app_article_all')]
    public function showArticleAll(ArticleRepository $articleRepository): Response
    {   
        $articles = $articleRepository->findAll();
        $article = $articleRepository->find(1);
        //$articleByDate = $articleRepository->findOneBy(['date'=>'2023-01-01']);

        //findBy sélectionnera que des égalité est non > ou <.
        //$articlesByDate = $articleRepository->findBy(['date'=>'2023-01-01']);
        //dd($articles);
        return $this->render('article/lesArticles.html.twig', [
            'listeArticles' => $articles,
            // 'article' => $article,
            // 'articleByDate' => $articleByDate,
            // 'articlesByDate' => $articlesByDate,
        ]);
    }

    #[Route('/article/id/{id}', name: 'app_article_id')]
    public function showArticle(ArticleRepository $articleRepository,$id): Response
    {   
        
        $article = $articleRepository->find($id);
        
        return $this->render('article/unArticle.html.twig', [
            //'listeArticles' => $articles,
            'article' => $article,
            // 'articleByDate' => $articleByDate,
            // 'articlesByDate' => $articlesByDate,
        ]);
    }

    #[Route('/article/add', name:'app_article_add')]
    public function addArticle():Response{
        
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
    
        return $this->render('article/addArticle.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

}
