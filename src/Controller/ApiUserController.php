<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ApiUserController extends AbstractController
{
    /**
     * @Route("/api/users", name="api_user",methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Returns the list of registered users linked to a client on the website",
     *
     *      @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class, groups={"user:read"}))
     * )
     * )
     * @OA\Tag(name="User")
     * @OA\Parameter(
     *     in="query",
     *     name="page",
     *     required=false,
     *     description="the page to recover ")
     * @throws InvalidArgumentException
     */
    public function index(UserRepository $userRepository,
                          PaginatorInterface $paginator,
                          Request $request,
                          CacheInterface $cache ): Response
    {
       $users = $cache->get('users',function (ItemInterface $item) use ($userRepository){
           return $userRepository->findAll();
       });

        $users = $paginator->paginate($users,$request->query->getInt('page',1),10);
        return $this->json($users, 200,[],['groups'=>'user:read','pagination'=>$users]);

    }


    /**
     * @Route("/api/users/{id}", name="api_user_detail",methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Returns the details of a registered user linked to a client",
     *
     *      @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=User::class, groups={"user:read","user-detail:read"}))
     * )
     * )
     * @OA\Tag(name="User")
     * @throws InvalidArgumentException
     */
    public function detail(User $user, CacheInterface $cache): Response
    {

        return $cache->get('users'.$user->getId(),function (ItemInterface $item)use ($user){
            return $this->json($user, 200,[],[
                'groups'=>['user:read','user-detail:read']
            ]);
        });

    }


}
