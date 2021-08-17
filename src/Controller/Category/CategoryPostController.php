<?php

namespace App\Controller\Category;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryPostController extends AbstractController
{
    /**
     * @Route("/api/categories/{categoryId}/posts", name="list_post_by_categories", methods={"GET"})
     */
    public function index($categoryId,CategoryRepository $categoryRepository , PostRepository $postRepository)
    {


        try {
            $category =$categoryRepository->find($categoryId);
            if(!$category){
                return $this->json(
                    [
                        'status'=>404,
                        'message'=>'la categories '.$categoryId.' n`\'existe pas'

                    ]);
            }
            $posts = $category->getPosts();

            return $this->json($posts, 201,[],['groups'=> 'post:read']);

        } catch (NotEncodableValueException $exception){
            return $this->json([
                'status'=>400,
                'message'=>"Syntax error"
            ]);
        }
    }

    /**
     * @Route("api/categories/{categoryId}/posts", name="store_post_by_category", methods={"POST"})
     */

    public function store( $categoryId,
                           Request $request,
                          SerializerInterface $serializer,
                          EntityManagerInterface $entityManager,
                          ValidatorInterface $validator,
                          CategoryRepository $categoryRepository


    ){
        $postRecu = $request->getContent();
        try {
            $category =$categoryRepository->find($categoryId);

            if (!$category){
                return $this->json(
                    [
                        'status'=>404,
                        'message'=>'cette categorie n\'existe pas '.$categoryId

                    ]);
            }
            $post= $serializer->deserialize($postRecu,Post::class,'json');
            $post->setCreatedAt(new \DateTime());
            $errors =$validator->validate($post);

            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $category->addPost($post);
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->json($post, 201,[],['groups'=> 'post:read']);
        } catch ( NotEncodableValueException $exception){
            return $this->json([
                'status'=>400,
                'message'=>"Syntax error"
            ]);
        }
    }

}
