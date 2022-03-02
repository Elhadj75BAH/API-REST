<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

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
     *     description="Return to the list of BileMo products",
     *       @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref=@Model(type=Product::class, groups={"product-list:read"}))
     * )
     * )
     *  @OA\Tag(name="Product")
     * @OA\Parameter(in="query", name="page", required=false,  description=" the page to recover")
     */
    public function index(PaginatorInterface $paginator , Request $request, CacheInterface $cache): Response
    {
        $products = $cache->get('products',function (ItemInterface $item){
            $item->expiresAfter(3);
            return  $this->entitymanager->getRepository(Product::class)->findAll();
        });

        $products = $paginator->paginate($products,$request->query->getInt('page',1),10);

        $response = $this->json($products,200,[],['groups'=>'product-list:read']);
        // On envoie la réponse*/
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
     * @OA\Tag(name="Product")
     */
    public function detail($id, CacheInterface $cache): Response
    {
        $product = $cache->get('product',function (ItemInterface $item)use ($id){
            $item->expiresAfter(3);
            return $this->entitymanager->getRepository(Product::class)->findOneById($id);
        });


        $response = $this->json($product,200,[],['groups'=>'product:read']);
        return $response;


    }
}
