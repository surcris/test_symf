<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Utils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/add', name: 'app_user_add')]
    public function addUser(EntityManagerInterface $em,Request $request,UserRepository $repo,UserPasswordHasherInterface $hash): Response
    {
        $msg = "";
        //Instancier un objet User
        $user = new User();
        //instancier un objet formulaire
        $form = $this->createForm(UserType::class, $user);
        //récupérer les données
        $form->handleRequest($request);
        //récupération d'un compte utilisateur
        $recup = $repo->findOneBy(['email'=>$user->getEmail()]);
        //test si le formulaire est submit
        if($form->isSubmitted() AND $form->isValid()){
            //tester si le compte existe
            if($recup){
                $msg = "Le compte : ".$user->getEmail()." existe déja";
            }
            else{
                //récupération du password
                $pass = Utils::cleanInputStatic($request->request->all('user')['password']['first']);
                //hashage du password
                $hash = $hash->hashPassword($user, $pass);
                //nettoyage des inputs
                $nom = Utils::cleanInputStatic($request->request->all('user')['nom']);
                $prenom = Utils::cleanInputStatic($request->request->all('user')['prenom']);
                $email = Utils::cleanInputStatic($request->request->all('user')['email']);
                //set des attributs nettoyé
                $user->setPassword($hash);
                $user->setNom($nom);
                $user->setPrenom($prenom);
                $user->setEmail($email);
                $user->setRoles(["ROLE_USER"]);
                //persister les données
                $em->persist($user);
                //ajoute en BDD
                $em->flush();
                $msg = "Le compte : ".$user->getEmail()." a été ajouté en BDD";
            }
        }
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
            'msg' => $msg,
        ]);
    }

    
}
