<?php

namespace App\Controller;

use App\Cache\PromotionCache;
use App\DTO\LowestPriceEnquiry;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Filter\PromotionsFilterInterface;
use App\Repository\ProductRepository;
use App\Service\Serializer\DTOSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductRepository $repository,
        private EntityManagerInterface $entityManager
    ) {
        # code...
    }
    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        PromotionsFilterInterface $promotionsFilter,
        CacheInterface $cache,
        PromotionCache $promotionCache
    ): Response {
        // if ($request->headers->has('force_fail')) {
        //     return new JsonResponse(
        //         ['error' => 'promossion engine failed'],
        //         $request->headers->get('force_fail')

        //     );
        // }

        //deserialize json data into a enquiryDTO 
        $lowestPriceEnquiry = $serializer->deserialize(
            $request->getContent(),
            LowestPriceEnquiry::class,
            'json'
        );

        // $product = $this->repository->find($id);
        $product = $this->repository->findOrFail($id);

        $lowestPriceEnquiry->setProduct($product);

        $promotions = $promotionCache->findValidForProduct($product, $lowestPriceEnquiry->getRequestDate());

        // $promotions = $cache->get("find-valid-for-product-$id", function (ItemInterface $item) use ($lowestPriceEnquiry, $product) {

        //     $p = $this->entityManager->getRepository(Promotion::class)->findValidForProduct(
        //         $product,
        //         date_create_immutable($lowestPriceEnquiry->getRequestDate())
        //     );
        //     return $p;
        // });



        //pass the enquiry into a promotions filter
        //appropiate promotion will be applied
        $modifiedEnquiry = $promotionsFilter->apply($lowestPriceEnquiry, ...$promotions);

        $res =  $serializer->serialize($modifiedEnquiry, 'json');

        return new JsonResponse($res, 200, json: true);
    }
}
