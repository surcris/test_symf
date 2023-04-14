<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Categorie;
use App\Entity\User;
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {   
        $tabCats = [];
        $tabUsers = [];
        $faker = Faker\Factory::create('fr_FR');
        for ($i=0; $i < 10; $i++) { 
            $cat = new Categorie();
            $cat->setNom($faker->jobTitle());
            $manager->persist($cat);
            $tabCats[] = $cat;
        }
        // $product = new Product();

        for ($i=0; $i < 5; $i++) { 
            $user = new User();
            $user->setEmail($faker->email());
            $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
            $user->setPassword(password_hash('1234',PASSWORD_DEFAULT));
            $user->setNom($faker->lastName());
            $user->setPrenom($faker->firstName());
            
            $manager->persist($user);
            $tabUsers[] = $user;
        }

        for ($i=0; $i < 10; $i++) { 
            $article = new Article();
            $article->setTitre($faker->word(3,true));
            $article->setContenu($faker->word(3,true));
            $article->setDate(new \DateTimeImmutable($faker->date('Y-m-d')));
            $article->setUser($tabUsers[$faker->numberBetween(0,4)]);
            
            $article->addCategory($tabCats[$faker->numberBetween(0,3)]);
            $article->addCategory($tabCats[$faker->numberBetween(3,7)]);
            $article->addCategory($tabCats[$faker->numberBetween(7,9)]);

            $manager->persist($article);
        }

        


        $manager->flush();
    }
}
