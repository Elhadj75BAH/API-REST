<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user",methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Returns the list of registered users linked to a client on the website",
     * )
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->apiFindAll();

        $response = $this->json($users, 200,[],['groups'=>'user:read']);
        return $response;

    }


    /**
     * @Route("/api/users/{id}", name="api_user_detail",methods={"GET"})
     *
     *  @OA\Response(
     *     response="200",
     *     description="Returns the details of a registered user linked to a client",
     * )
     */
    public function detail(User $user): Response
    {
        $response = $this->json($user, 200,[],['groups'=>'user:read']);
        return $response;

    }


}
