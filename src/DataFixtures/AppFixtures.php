<?php

namespace App\DataFixtures;

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
       $users =Array();
        $posts = Array();
        $comments= Array();
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

       for ($i =0; $i<50; $i++){
           $posts[$i]= new Post();
           $posts[$i]->setTitle($faker->title);
           $posts[$i]->setContent($faker->text(255));
           $posts[$i]->setCreatedAt(new \DateTime());
            $manager->persist($posts[$i]);

           // On associe les commentaires pour ce poste
           $this->setComment($posts[$i],$manager);
           $manager->flush();

       }
        echo "Enregistrement Post OK \n";

        $manager->flush();
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

    }
}
