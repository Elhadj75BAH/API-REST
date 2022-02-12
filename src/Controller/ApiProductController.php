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
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class ApiProductController extends AbstractController
{
    private $entitymanager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entitymanager = $entityManager;
    }

    /**
     * @Route("/api/products", name="api_product", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *
     *     description="Return to the list of BileMo products")
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

    }


    /**
     * @Route("/api/products/{id}", name="api_product_detail", methods={"GET"})
     *
     * @OA\Response(
     *     response="200",
     *     description="Returns the details of a BileMo product",
     *
     *      @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Product::class, groups={"product:read"}))
     * )
     * )

     */
    public function detail($id): Response
    {
        $product = $this->entitymanager->getRepository(Product::class)->findOneById($id);

        $response = $this->json($product,200,[],['groups'=>'product:read']);
        return $response;


    }
}
