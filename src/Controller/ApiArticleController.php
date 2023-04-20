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
    public function addArticle(UserRepository $repoUser,CategorieRepository $repoCate,ArticleRepository $repoArt,Request $request,SerializerInterface $serializerInterface,EntityManagerInterface $em): Response
    {
        $json = $request->getContent();
        if(!$json){
            return $this->json(['erreur'=>'Le JSON est vide'],400,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }
        $data = $serializerInterface->decode($json,'json');
        $articles = new Article();
        $articles->setTitre($data['titre']);
        $articles->setContenu($data['contenu']);
        $articles->setDate(new \DateTimeImmutable($data['date']));
        $recupCate = $repoCate->findOneBy(['nom'=>'Logistique']);
        if(!$recupCate){
            return $this->json(['erreur'=>'La catégorie existe pas'],401,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }
        $articles->addCategory($recupCate);
        $recupCate = $repoCate->findOneBy(['nom'=>'Oenologue']);
        if(!$recupCate){
            return $this->json(['erreur'=>'La catégorie existe pas'],401,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }
        $articles->addCategory($recupCate);
        $recupUser = $repoUser->findOneBy(['email'=>'michelle32@tele2.fr']);
        if(!$recupUser){
            return $this->json(['erreur'=>'L\'utilisateur existe pas'],401,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }
        $articles->setUser($recupUser);
        //$articles->addCategory($recupCate[0]);
        //$articles->addCategory($recupCate[2]);
        //dd($articles);

        $recup = $repoArt->findOneBy(['titre'=>$data['titre'],'date'=>$articles->getDate()]);
        if($recup){
            return $this->json(['erreur'=>'L\'article '.$articles->getTitre().' existe déjà'],206,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }else{
            $em->persist($articles);
            $em->flush();
            return $this->json(['succés'=>'L\'article '.$articles->getTitre().' a été ajouter'],200,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'GET'],[]);
        }   
        
    }

    #[Route('/api/article/delete/{id}', name: 'app_api_article_delete',methods:'DELETE')]
    public function deleteArticle(EntityManagerInterface $em,ArticleRepository $repo,$id): Response
    {
        $msg = "";
        $article = $repo->find($id);
        //dd($article);

        if(!$article){
            return $this->json(['erreur'=>'L\'article '.$id.' existe pas'],206,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'DELETE'],[]);
        }else{
            $em->remove($article);
            $em->flush();
            return $this->json(['succés'=>'L\'article '.$article->getTitre().' a été supprimer'],200,['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> 'localhost',
            'Access-Control-Allow-Methods'=> 'DELETE'],[]);
        }   
        
    }

}

?>