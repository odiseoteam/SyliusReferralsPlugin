<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
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
    private FactoryInterface $affiliateFactory;
    private AffiliateRepositoryInterface $affiliateRepository;
    private RouterInterface $router;

    public function __construct(
        CustomerContextInterface $customerContext,
        ProductRepositoryInterface $productRepository,
        FactoryInterface $affiliateFactory,
        AffiliateRepositoryInterface $affiliateRepository,
        RouterInterface $router
    ) {
        $this->customerContext = $customerContext;
        $this->productRepository = $productRepository;
        $this->affiliateFactory = $affiliateFactory;
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

        /** @var AffiliateInterface $affiliate */
        $affiliate = $this->affiliateFactory->createNew();
        $affiliate->setType(AffiliateInterface::TYPE_PROMOTION);
        $affiliate->setCustomer($customer);
        $affiliate->setProduct($product);

        $link = $this->generateLink($affiliate);

        $this->affiliateRepository->add($affiliate);

        $data = [
            'link' => $link,
            'responseURL' => $request->getUri(),
        ];

        return new JsonResponse($data);
    }

    private function generateLink(AffiliateInterface $affiliate): string
    {
        $affiliate->setTokenValue($this->generateTokenValue());

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

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }
}
