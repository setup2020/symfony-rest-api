<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\CommentRepository;

class PostController extends AbstractController
{
    /**
     * @Route("api/posts", name="list_posts",methods={"GET"})
     */
    public function index(PostRepository $postRepository)
    {
        return $this->json($postRepository->findAll(),200,[],['groups'=> 'post:read']);
    }
    /**
     * @Route("api/posts/{id}", name="show_posts",methods={"GET"})
     */
    public function show($id ,PostRepository $postRepository){
        return $this->json($postRepository->find($id),200,[],['groups'=> 'post:read']);
    }
    /**
     * @Route("api/posts", name="store_post", methods={"POST"})
     */

    public function store(Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager,
    ValidatorInterface $validator){
        $postRecu = $request->getContent();
        try {
            $post= $serializer->deserialize($postRecu,Post::class,'json');
            $post->setCreatedAt(new \DateTime());
             $errors =$validator->validate($post);
             if(count($errors)>0){
                 return $this->json($errors,400);
             }
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


    /**
     * @Route("api/posts/{postId}", name="delete_comment" , methods={"DELETE"})
     */

    public function delete($postId,PostRepository $postRepository,EntityManagerInterface $entityManager){
        $post=$postRepository->find($postId);
        if (!$post){
            return $this->json(['status'=>404,'message'=>"post introuvable"]);
        }
        $entityManager->remove($post);
        $entityManager->flush();
        return $this->json(['status'=>200,'message'=>'suppression effectué']);

    }

      /**
     * @Route("api/posts/{postId}/comments/{commentsId}", name="delete_comment" , methods={"DELETE"})
     */

    public function deleteComment($postId,$commentsId,CommentRepository $commentRepository,EntityManagerInterface $entityManager){
        $comment=$commentRepository->findOneBy(array('post_id'=>$postId,'id'=>$commentsId));
        if (!$comment){
            return $this->json(['status'=>404,'message'=>"commentaire introuvable"]);
        }
        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->json(['status'=>200,'message'=>'suppression effectué']);

    }
}
