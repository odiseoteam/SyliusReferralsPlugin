<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Generator\AffiliateGeneratorInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class CreateFromProductAffiliateAction
{
    private CustomerContextInterface $customerContext;
    private ProductRepositoryInterface $productRepository;
    private AffiliateGeneratorInterface $affiliateGenerator;
    private AffiliateRepositoryInterface $affiliateRepository;
    private RouterInterface $router;

    public function __construct(
        CustomerContextInterface $customerContext,
        ProductRepositoryInterface $productRepository,
        AffiliateGeneratorInterface $affiliateGenerator,
        AffiliateRepositoryInterface $affiliateRepository,
        RouterInterface $router
    ) {
        $this->customerContext = $customerContext;
        $this->productRepository = $productRepository;
        $this->affiliateGenerator = $affiliateGenerator;
        $this->affiliateRepository = $affiliateRepository;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $productId = $request->attributes->get('id');

        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($productId);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $affiliate = $this->affiliateRepository->findOneByCustomerAndProductNotExpired($customer, $product);
        if ($affiliate === null) {
            $affiliate = $this->affiliateGenerator->generate($customer, $product);
        }

        $link = $this->generateLink($affiliate);

        $data = [
            'link' => $link,
            'responseURL' => $request->getUri(),
        ];

        return new JsonResponse($data);
    }

    private function generateLink(AffiliateInterface $affiliate): string
    {
        /** @var ProductInterface $product */
        $product = $affiliate->getProduct();

        return $this->router->generate(
            'sylius_shop_product_show',
            [
                'slug' => $product->getSlug(),
                AffiliateInterface::TOKEN_PARAM_NAME => $affiliate->getTokenValue()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
