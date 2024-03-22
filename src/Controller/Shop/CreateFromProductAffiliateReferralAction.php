<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Odiseo\SyliusReferralsPlugin\Generator\AffiliateReferralGeneratorInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralRepositoryInterface;
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

final class CreateFromProductAffiliateReferralAction
{
    private CustomerContextInterface $customerContext;

    private ProductRepositoryInterface $productRepository;

    private AffiliateReferralGeneratorInterface $affiliateReferralGenerator;

    private AffiliateReferralRepositoryInterface $affiliateReferralRepository;

    private RouterInterface $router;

    public function __construct(
        CustomerContextInterface $customerContext,
        ProductRepositoryInterface $productRepository,
        AffiliateReferralGeneratorInterface $affiliateReferralGenerator,
        AffiliateReferralRepositoryInterface $affiliateReferralRepository,
        RouterInterface $router,
    ) {
        $this->customerContext = $customerContext;
        $this->productRepository = $productRepository;
        $this->affiliateReferralGenerator = $affiliateReferralGenerator;
        $this->affiliateReferralRepository = $affiliateReferralRepository;
        $this->router = $router;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        if (!$customer instanceof AffiliateInterface) {
            throw new NotFoundHttpException();
        }

        $productId = $request->attributes->get('id');

        /** @var ProductInterface|null $product */
        $product = $this->productRepository->find($productId);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        $affiliateReferral = $this->affiliateReferralRepository->findOneByAffiliateAndProductNotExpired(
            $customer,
            $product,
        );

        if ($affiliateReferral === null) {
            $affiliateReferral = $this->affiliateReferralGenerator->generate($customer, $product);
        }

        $link = $this->generateLink($affiliateReferral);

        $data = [
            'link' => $link,
            'responseURL' => $request->getUri(),
        ];

        return new JsonResponse($data);
    }

    private function generateLink(AffiliateReferralInterface $affiliateReferral): string
    {
        /** @var ProductInterface $product */
        $product = $affiliateReferral->getProduct();

        return $this->router->generate(
            'sylius_shop_product_show',
            [
                'slug' => $product->getSlug(),
                AffiliateReferralInterface::TOKEN_PARAM_NAME => $affiliateReferral->getTokenValue(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
