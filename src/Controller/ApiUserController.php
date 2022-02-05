<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user",methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->apiFindAll();

        $response = $this->json($users, 200,[],['groups'=>'user:read']);
        return $response;

    }


    /**
     * @Route("/api/users/{id}", name="api_user_detail",methods={"GET"})
     */
    public function detail(User $user): Response
    {
        $response = $this->json($user, 200,[],['groups'=>'user:read']);
        return $response;

    }


}
