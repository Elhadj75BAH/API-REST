<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="api_client", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        $response = $this->json($clients,200,[],['groups'=>'client:read']);
        return $response;
    }

    /**
     * @Route("/api/client/{id}", name="api_client_detail", methods={"GET"})
     */
    public function details(ClientRepository $clientRepository, $id): Response
    {
        $clients = $clientRepository->findOneById($id);

        $response = $this->json($clients,200,[],['groups'=>'client:read']);
        return $response;
    }


}
