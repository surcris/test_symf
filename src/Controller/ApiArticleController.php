<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\CategorieRepository;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ApiArticleController extends AbstractController
{

    #[Route('/api/article/all', name: 'app_api_article_all',methods:'GET')]
    public function getArticle(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        if (empty($articles)){
            return $this->json(['erreur'=>'Il n\'y a pas d\'article'],206,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],['groups'=>'article:readAll']);
        }else{
            return $this->json($articles,200,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],
            ['groups'=>'article:readAll']);
        }
        //dd($articles);
        
    }

    #[Route('/api/article/id/{id}', name: 'app_api_article_id',methods:'GET')]
    public function getArticleById(ArticleRepository $repo,int $id): Response
    {
        $articles = $repo->find($id);
        if (empty($articles)){
            return $this->json(['erreur'=>'Il n\'y a pas d\'article'],206,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],['groups'=>'article:id']);
        }else{
            return $this->json($articles,200,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],
            ['groups'=>'article:readAll']);
        }
        //dd($articles);
        
    }

    #[Route('/api/article/add', name: 'app_api_article_add',methods:'GET')]
    public function addArticle(CategorieRepository $repoCate,ArticleRepository $repoArt,Request $request,SerializerInterface $serializerInterface,EntityManagerInterface $em): Response
    {
        $json = $request->getContent();
        $data = $serializerInterface->decode($json,'json');
        $articles = new Article();
        $articles->setTitre($data['titre']);
        $articles->setContenu($data['contenu']);
        $articles->setDate(new \DateTimeImmutable($data['date']));
        $recupCate = $repoCate->findOneBy(['nom'=>'Logistique']);
        $articles->addCategory($recupCate);
        $recupCate = $repoCate->findOneBy(['nom'=>'Oenologue']);
        $articles->addCategory($recupCate);
        //$articles->addCategory($recupCate[0]);
        //$articles->addCategory($recupCate[2]);
        dd($articles);
        // $recup = $repoArt->findOneBy(['titre'=>$data['titre'],'date'=>$articles->getDate()]);
        
        // if($recup){
        //     return $this->json(['erreur'=>'L\'article '.$articles->getTitre().' existe déjà'],206,['Content-Type'=>'application/json',
        //     'Access-Control-Allow-Origin'=> 'localhost',
        //     'Access-Control-Allow-Methods'=> 'GET'],[]);
        // }
        // $em->persist($articles);
        // $em->flush();
        // //dd($articles);
        
        // return $this->json(['succés'=>'L\'article '.$articles->getTitre().' a été ajouter'],206,['Content-Type'=>'application/json',
        // 'Access-Control-Allow-Origin'=> 'localhost',
        // 'Access-Control-Allow-Methods'=> 'GET'],[]);
    }

}

?>