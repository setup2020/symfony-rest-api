<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="list_categories" method={"GET"})
     */
    public function index(CategoryRepository $categoryRepository)
    {
        return $this->json($categoryRepository->findAll(),200,[],['groups'=> 'post:read']);
    }
    /**
     * @Route("api/categories", name="store_categories", methods={"POST"})
     */
    public function store(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager,
                          ValidatorInterface $validator){

        $categoryRecu=$request->getContent();

        try {
            $category= $serializer->deserialize($categoryRecu,Category::class,'json');
            $errors =$validator->validate($category);
            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $entityManager->persist($category);
            $entityManager->flush();
            return $this->json($category, 201,[],['groups'=> 'category:read']);
        } catch ( NotEncodableValueException $exception){
            return $this->json([
                'status'=>400,
                'message'=>"Syntax error"
            ]);
        }

    }
}
