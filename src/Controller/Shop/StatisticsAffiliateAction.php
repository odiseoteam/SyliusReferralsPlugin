<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

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
    private AffiliateViewRepositoryInterface $affiliateViewRepository;
    private Environment $twig;

    public function __construct(
        CustomerContextInterface $customerContext,
        OrderRepositoryInterface $orderRepository,
        AffiliateViewRepositoryInterface $affiliateViewRepository,
        Environment $twig
    ) {
        $this->customerContext = $customerContext;
        $this->orderRepository = $orderRepository;
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

        $sales = $this->orderRepository->countSalesByCustomer($customer);
        $visits = $this->affiliateViewRepository->countViewsByCustomer($customer);
        $average = $this->getAverage($sales, $visits);

        $data = [
            'sales' => $sales,
            'visits' => $visits,
            'average' => $average
        ];

        $content = $this->twig->render(
            '@OdiseoSyliusReferralsPlugin/Shop/Account/Affiliate/Index/Statistics/_template.html.twig',
            $data
        );

        return new Response($content);
    }

    private function getAverage(int $sales, int $visits): int
    {
        if ($sales == 0 || $visits == 0) {
            return 0;
        }

        return (int) (($sales / $visits) * 100);
    }
}
