<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommentController extends AbstractController
{
    /**
     * @Route("api/posts/{id}/comments", name="store_comment",methods={"POST"})
     */

    public function store($id,Request $request,SerializerInterface $serializer, EntityManagerInterface $entityManager,PostRepository $postRepository,
                          ValidatorInterface $validator){
        $commentRecu = $request->getContent();


        try {
            $post=$postRepository->find($id);
            if (!$post){
                return $this->json(
                    [
                        'status'=>404,
                        'message'=>'le poste '.$id

                    ]);
            }
            $comment= $serializer->deserialize($commentRecu,Comment::class,'json');
            $comment->setCreatedAt(new \DateTime());
            $errors =$validator->validate($post);
            if(count($errors)>0){
                return $this->json($errors,400);
            }

            $post->addComment($comment);
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->json($comment, 201,[],['groups'=> 'comment:read']);
        }
        catch ( NotEncodableValueException $exception){
            return $this->json([
                'status'=>400,
                'message'=>"Syntax error"
            ]);
        }
    }

  
}
