<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiProductController extends AbstractController
{
    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route("/api/products", name="api_product", methods={"GET"})
     */
    public function index(): Response
    {
        $products = $this->entitymanager->getRepository(Product::class)->apiFindAll();
        // On indique qu'on utilise un json_encoder
        $encoders = [new JsonEncoder()];
        // On instancie le normalisez pour convertir la collection en un tableau
        $normalizers = [new ObjectNormalizer()];
        // On converti en json
        // On instancie le convertisseur
        $serialser = new Serializer($normalizers, $encoders);
        // On converti en json
        $jsonContent = $serialser->serialize($products, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // On instancie la réponse
        $response = new Response($jsonContent);
        // On ajoute l'entête HTTP
        $response->headers->set('Content-Type', 'application/json');

        // On envoie la réponse
        return $response;

        /* return $this->json([
             'product' => $products,
         ]);*/
    }
}
