<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ApiClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="api_client", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Return the list of clients",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Client::class, groups={"client:read"}))
     * )
     * )
     * @OA\Tag(name="Client")
     * @throws InvalidArgumentException
     */
    public function index(ClientRepository $clientRepository,
                          CacheInterface $cache): Response
    {
        $clients = $cache->get('clients',function (ItemInterface $item)use ($clientRepository){
            return $clientRepository->findAll();
        });
        return $this->json($clients,200,[],['groups'=>'client:read']);
    }

    /**
     * @Route("/api/clients/{id}", name="api_client_detail", methods={"GET"})
     * @OA\Response(
     *     response="200",
     *     description="Returns the details of a client with associated users",
     *
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Client::class, groups={"client:read","client-detail:read"}))
     * )
     * )
     * @OA\Tag(name="Client")
     * @throws InvalidArgumentException
     */
    public function details(Client $client, CacheInterface $cache): Response
    {
        return $cache->get('clients'.$client->getId(),function (ItemInterface $item)use ($client){
            return $this->json($client, 200,[],[
                'groups'=>['client:read','client-detail:read']
            ]);
        });
    }

    /**
     * @Route("/api/clients/{id}/users", name="api_addUser_",methods={"POST"})
     * @OA\Response(
     *     response="201",
     *     description="adds a new user linked to a client",
     * )
     * @OA\Tag(name="Client")
     * @throws InvalidArgumentException
     */
    public function addUser( Request $request,
                             SerializerInterface $serializer,
                             EntityManagerInterface $em,
                             ValidatorInterface $validator, Client $client,
                             CacheInterface $cache): Response
    {
        $cache->delete('clients');
        $jsonAdd = $request->getContent();
        try {
            /** @var User $userAdd */
            $userAdd = $serializer->deserialize($jsonAdd,User::class,'json');
            $userAdd->setClient($client);
            $errors = $validator->validate($userAdd);
            // Verification si ya erreur
            if (count($errors)>0){
                return $this->json($errors,400);
            }

            $em->persist($userAdd);
            $em->flush();

            return $this->json($userAdd,201,[],['groups'=>'user:read']);

        }catch (NotEncodableValueException $e){
           return $this->json([
               'status'=>400,
               'message'=>$e->getMessage()
           ], 400);
        }

    }

    /**
     * @Route("/api/clients/{id}/users", name="api_User_delete",methods={"DELETE"})
     *
     * @OA\Response(
     *     response="204",
     *     description="deletes a user added by a client")
     * @OA\Tag(name="Client")
     * @throws InvalidArgumentException
     */
    public function delete(EntityManagerInterface $em, User $user , CacheInterface $cache): Response
    {
            $cache->delete("clients");
            $em->remove($user);
            $em->flush();
            return $this->json(null,204,[]);
    }

}
