<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user",methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Returns the list of registered users linked to a client on the website",
     * )
     * @OA\Tag(name="User")
     * @OA\Parameter(in="query",name="page",required=false,description="page à recuperer ")
     */
    public function index(UserRepository $userRepository,
                          PaginatorInterface $paginator,
                          Request $request,
                          CacheInterface $cache ): Response
    {
       $users = $cache->get('users',function (ItemInterface $item)use ($userRepository, $cache){
           $item->expiresAfter(30);
           return $userRepository->findAll();
       });

        $users = $paginator->paginate($users,$request->query->getInt('page',1),10);
        $response = $this->json($users, 200,[],['groups'=>'user:read','pagination'=>$users]);
        return $response;

    }


    /**
     * @Route("/api/users/{id}", name="api_user_detail",methods={"GET"})
     *
     *  @OA\Response(
     *     response="200",
     *     description="Returns the details of a registered user linked to a client",
     * )
     * @OA\Tag(name="User")
     */
    public function detail(User $user, CacheInterface $cache): Response
    {
        $response = $cache->get('users',function (ItemInterface $item)use ($user){
            $item->expiresAfter(3);
            return $this->json($user, 200,[],['groups'=>'user:read']);
        });

        return $response;

    }


}
