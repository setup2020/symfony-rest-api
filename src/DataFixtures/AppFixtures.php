<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use  Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function load(ObjectManager $manager)
    {
       $faker =Faker\Factory::create('fr_FR');
        $categories= Array();
        for ($i=0; $i<10; $i++){
            $categories[$i]= new Category();
            $categories[$i]->setName($faker->name);
            $categories[$i]->setDiscription($faker->text);
            $categories->persist($categories[$i]);
            // On associe les posts
            $this->setPost($categories[$i],$manager);
//            $manager->flush();
        }
        echo "Enregistrement categories OK \n";
        $users =Array();
       for ($i = 0; $i<20; $i++){
           $users[$i]= new User();
           $users[$i]->setName($faker->name);
           $users[$i]->setEmail($faker->email);
           $users[$i]->setPhoto($faker->address);
          // $password =$this->encoder->encodePassword($users[$i],'secret');
           $users[$i]->setPassword('password');
           $manager->persist($users[$i]);



       }
       echo "Enregistrement utilisateurs OK \n";
        $manager->flush();
    }

    public  function setPost(Category $category, ObjectManager $manager){
        for ($j= 0 ; $j<5; $j++){
            $faker =Faker\Factory::create('fr_FR');
            $posts[$j]= new Post();
            $posts[$j]->setContent($faker->text(255));
            $category->addPost($posts[$j]);              // ou $facture->setUser($user);
            $manager->persist($posts[$j]);
            // On associe les commentaires pour ce poste
            $this->setComment($posts[$j],$manager);
            $manager->flush();

        }

        echo "Enregistrement des posts OK \n";
    }

    public function setComment(Post $post,ObjectManager $manager){
        $faker =Faker\Factory::create('fr_FR');
        for ($j= 0 ; $j<6; $j++){
            $comments[$j]= new Comment();
            $comments[$j]->setCreatedAt(new \DateTime());
            $comments[$j]->setContent($faker->text(255));
            $post->addComment($comments[$j]);              // ou $facture->setUser($user);
            $manager->persist($comments[$j]);

        }
        echo "Enregistrement commentaires OK \n";
    }
}
