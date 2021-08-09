<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("api/users", name="api_list_users", methods={"GET"})
     */
    public function index(UserRepository $userRepository)
    {
       return $this->json($userRepository->findAll(),200,[],['groups'=> 'user:read']);
    }

    /**
     * @Route("api/users/{id}", name="api_show_user", methods={"GET"})
     */

    public function show($id , UserRepository $userRepository){
        $user = $userRepository->findOneBy(['id' => $id]);
        return $this->json($user,200 ,[],['groups'=>'user:read']);
    }
}
