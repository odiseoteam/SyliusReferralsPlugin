<?php

/*
* This file is part of the Odiseo Referrals Plugin package, a commercial software.
* Only users who have purchased a valid license and accept to the terms of the License Agreement can install
* and use this program.
*
* Copyright (c) 2018-2022 Odiseo - Pablo D'amico
*/

namespace Odiseo\SyliusReferralsPlugin\Controller;

use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgram;
use Odiseo\SyliusReferralsPlugin\Entity\ReferralsProgramInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepository;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramViewRepository;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Webmozart\Assert\Assert;

class ReferralsProgramController extends ResourceController
{
    public function getStatistics(Request $request): Response
    {
        /** @var string $template **/
        $template = $request->attributes->get('template');

        /** @var ShopUserInterface|null $user **/
        $user = $this->getUser();

        $customer = $this->getCustomer($user);

        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $dateTime = new \DateTime();
        $repository = $this->getReferralsProgramRepository();

        $maxViewsReferedPage    = $repository->findMaxViewReferedPageByCustomer($customer);
        $maxProductReferedPage  = $repository->findMaxProductReferedPageByCustomer($customer);
        $average                = $this->averageByCustomer($customer);
        $monthReferrals         = $this->getReferralsProgramViewRepository()
                                    ->findMonthReferralsByCustomer($customer, $dateTime);

        return $this->render($template, [
            'maxViewsReferedPage' => $maxViewsReferedPage,
            'maxProductReferedPage' => $maxProductReferedPage,
            'average' => $average,
            'monthReferrals' => $monthReferrals,
        ]);
    }

    public function createFromProduct(Request $request): Response
    {
        $product = $this->getProductRepository()->find($request->query->getInt('product'));
        Assert::notNull($product);

        /** @var ShopUserInterface|null $user **/
        $user = $this->getUser();

        $customer = $this->getCustomer($user);

        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $referralsProgram = new ReferralsProgram();
        $referralsProgram->setCustomer($customer);
        $referralsProgram->setProduct($product);

        $this->generateLink($referralsProgram);

        $em = $this->getDoctrine()->getManager();
        $em->persist($referralsProgram);
        $em->flush();

        return $this->json([
            'link' => $referralsProgram->getLink(),
            'responseURL' => $request->getUri(),
        ]);
    }

    private function getCustomer(?ShopUserInterface $user): ?CustomerInterface
    {
        return $user instanceof ShopUserInterface && null !== $user->getCustomer() ? $user->getCustomer() : null;
    }

    private function getProductRepository(): ProductRepository
    {
        /** @var ProductRepository $productRepository **/
        $productRepository = $this->get('sylius.repository.product');

        return $productRepository;
    }

    private function getReferralsProgramRepository(): ReferralsProgramRepository
    {
        /** @var ReferralsProgramRepository $referralsProgramRepository **/
        $referralsProgramRepository = $this->get('odiseo_sylius_referrals_plugin.repository.referrals_program');

        return $referralsProgramRepository;
    }

    private function getReferralsProgramViewRepository(): ReferralsProgramViewRepository
    {
        /** @var ReferralsProgramViewRepository $referralsProgramViewRepository **/
        $referralsProgramViewRepository = $this
                                            ->get('odiseo_sylius_referrals_plugin.repository.referrals_program_view');

        return $referralsProgramViewRepository;
    }

    private function generateLink(ReferralsProgramInterface $referralsProgram): void
    {
        $referralsProgram->setTokenValue($this->generateTokenValue());

        $product = $referralsProgram->getProduct();
        Assert::notNull($product);

        /** @var RouterInterface $router **/
        $router = $this->get('router');

        $link = $router->generate(
            'sylius_shop_product_show',
            [
                'slug' => $product->getSlug(),
                ReferralsProgramInterface::TOKEN_PARAM_NAME => $referralsProgram->getTokenValue()
            ],
            UrlGenerator::ABSOLUTE_URL
        );
        $referralsProgram->setLink($link);
    }

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }

    private function averageByCustomer(CustomerInterface $customer): int
    {
        $sum = $this->getReferralsProgramViewRepository()->findViewsByCustomer($customer);
        $count = $this->getReferralsProgramRepository()->findCountPaymentsByCustomer($customer);

        if ($count == 0 || $sum == 0) {
            return 0;
        }

        return (int) (($count / $sum) * 100);
    }
}
