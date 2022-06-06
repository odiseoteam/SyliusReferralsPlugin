<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Repository\AffiliateRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateViewRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

final class StatisticsAffiliateAction
{
    private CustomerContextInterface $customerContext;
    private OrderRepositoryInterface $orderRepository;
    private AffiliateRepositoryInterface $affiliateRepository;
    private AffiliateViewRepositoryInterface $affiliateViewRepository;
    private Environment $twig;

    public function __construct(
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        AffiliateRepositoryInterface $affiliateRepository,
        AffiliateViewRepositoryInterface $affiliateViewRepository,
        Environment $twig
    ) {
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
        $this->affiliateRepository = $affiliateRepository;
        $this->affiliateViewRepository = $affiliateViewRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $dateTime = new \DateTime();

        $maxViewsReferredPage = $this->affiliateRepository->findMaxViewReferredPageByCustomer($customer);
        $maxProductReferredPage = $this->affiliateRepository->findMaxProductReferredPageByCustomer($customer);
        $average = $this->averageByCustomer($customer);
        $monthReferrals = $this->affiliateViewRepository->findMonthReferralsByCustomer($customer, $dateTime);

        $data = [
            'maxViewsReferredPage' => $maxViewsReferredPage,
            'maxProductReferredPage' => $maxProductReferredPage,
            'average' => $average,
            'monthReferrals' => $monthReferrals,
        ];

        $content = $this->twig->render(
            '@OdiseoSyliusReferralsPlugin/Shop/Account/Affiliate/Index/Statistics/_template.html.twig',
            $data
        );

        return new Response($content);
    }

    private function averageByCustomer(CustomerInterface $customer): int
    {
        $sum = $this->affiliateViewRepository->findViewsByCustomer($customer);
        $count = $this->orderRepository->findCountAffiliateSalesByCustomer($customer);

        if ($count == 0 || $sum == 0) {
            return 0;
        }

        return (int) (($count / $sum) * 100);
    }
}
