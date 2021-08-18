<?php

namespace App\Controller\Category;

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
     * @Route("api/categories", name="list_categories", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository){
        return $this->json($categoryRepository->findAll(),200,[],['groups'=> 'category:read']);
    }
    /**
     * @Route("api/categories/{id}", name="show_category", methods={"GET"})
     */
    public function show($id ,CategoryRepository $categoryRepository){
        if(!$categoryRepository){
            return $this->json(
                [
                    'status'=>404,
                    'message'=>'la categories  n`\'existe pas'

                ]);
        }

        return $this->json($categoryRepository->find($id), 201,[],['groups'=> 'category:read']);

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
    /**
     * @Route("api/categories/{categoryId}/posts, name="list_posts_category", methods={"GET"})
     */
    public function categoryPosts($categoryId,CategoryRepository $categoryRepository){

        try {
            $category= $categoryRepository->find($categoryId);
            if (!$category){
                return $this->json(
                    [
                        'status'=>404,
                        'message'=>'la category '.$categoryId . ' n\'existe pas'

                    ]);
            }

        } catch ( NotEncodableValueException $exception){
            return $this->json([
                'status'=>400,
                'message'=>"Syntax error"
            ]);
        }


    }

}
