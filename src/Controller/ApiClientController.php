<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\User;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="api_client", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->apiFindAll();

        $response = $this->json($clients,200,[],['groups'=>'client:read']);
        return $response;
    }

    /**
     * @Route("/api/clients/{id}", name="api_client_detail", methods={"GET"})
     */
    public function details(ClientRepository $clientRepository, $id): Response
    {
        $clients = $clientRepository->findOneById($id);

        $response = $this->json($clients,200,[],['groups'=>'client:read']);
        return $response;
    }


    /**
     * @Route("/api/clients/{id}/users", name="api_addUser_",methods={"POST"})
     */
    public function addUser( Request $request,
                             SerializerInterface $serializer,
                             EntityManagerInterface $em,
                            ValidatorInterface $validator, Client $client): Response
    {

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
     */
    public function delete(EntityManagerInterface $em, User $user): Response
    {
            $em->remove($user);
            $em->flush();
            return $this->json(null,204,[]);
    }


}
