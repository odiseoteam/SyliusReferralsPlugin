<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
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

final class CreateFromProductReferralsProgramAction
{
    private CustomerContextInterface $customerContext;
    private ProductRepositoryInterface $productRepository;
    private FactoryInterface $referralsProgramFactory;
    private ReferralsProgramRepositoryInterface $referralsProgramRepository;
    private RouterInterface $router;

    public function __construct(
        CustomerContextInterface $customerContext,
        ProductRepositoryInterface $productRepository,
        FactoryInterface $referralsProgramFactory,
        ReferralsProgramRepositoryInterface $referralsProgramRepository,
        RouterInterface $router
    ) {
        $this->customerContext = $customerContext;
        $this->productRepository = $productRepository;
        $this->referralsProgramFactory = $referralsProgramFactory;
        $this->referralsProgramRepository = $referralsProgramRepository;
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

        $product = $this->productRepository->find($productId);
        if (null === $product) {
            throw new NotFoundHttpException();
        }

        /** @var ReferralsProgramInterface $referralsProgram */
        $referralsProgram = $this->referralsProgramFactory->createNew();
        $referralsProgram->setCustomer($customer);
        $referralsProgram->setProduct($product);

        $this->generateLink($referralsProgram);

        $this->referralsProgramRepository->add($referralsProgram);

        $data = [
            'link' => $referralsProgram->getLink(),
            'responseURL' => $request->getUri(),
        ];

        return new JsonResponse($data);
    }

    private function generateLink(ReferralsProgramInterface $referralsProgram): void
    {
        $referralsProgram->setTokenValue($this->generateTokenValue());

        $product = $referralsProgram->getProduct();

        $link = $this->router->generate(
            'sylius_shop_product_show',
            [
                'slug' => $product->getSlug(),
                ReferralsProgramInterface::TOKEN_PARAM_NAME => $referralsProgram->getTokenValue()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $referralsProgram->setLink($link);
    }

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }
}
