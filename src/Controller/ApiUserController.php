<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users-client", name="api_user",methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        $response = $this->json($users, 200,[],['groups'=>'user:read']);
        return $response;

    }


    /**
     * @Route("/api/user-client/{id}", name="api_user",methods={"GET"})
     */
    public function detail($id,UserRepository $userRepository): Response
    {
        $users = $userRepository->findOneById($id);

        $response = $this->json($users, 200,[],['groups'=>'user:read']);
        return $response;

    }
}
