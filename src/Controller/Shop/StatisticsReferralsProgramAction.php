<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Controller\Shop;

use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramRepositoryInterface;
use Odiseo\SyliusReferralsPlugin\Repository\ReferralsProgramViewRepositoryInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

final class StatisticsReferralsProgramAction
{
    private CustomerContextInterface $customerContext;
    private ReferralsProgramRepositoryInterface $referralsProgramRepository;
    private ReferralsProgramViewRepositoryInterface $referralsProgramViewRepository;
    private Environment $twig;

    public function __construct(
        CustomerContextInterface $customerContext,
        ReferralsProgramRepositoryInterface $referralsProgramRepository,
        ReferralsProgramViewRepositoryInterface $referralsProgramViewRepository,
        Environment $twig
    ) {
        $this->customerContext = $customerContext;
        $this->referralsProgramRepository = $referralsProgramRepository;
        $this->referralsProgramViewRepository = $referralsProgramViewRepository;
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        $dateTime = new \DateTime();

        $maxViewsReferredPage = $this->referralsProgramRepository->findMaxViewReferredPageByCustomer($customer);
        $maxProductReferredPage = $this->referralsProgramRepository->findMaxProductReferredPageByCustomer($customer);
        $average = $this->averageByCustomer($customer);
        $monthReferrals = $this->referralsProgramViewRepository->findMonthReferralsByCustomer($customer, $dateTime);

        $data = [
            'maxViewsReferredPage' => $maxViewsReferredPage,
            'maxProductReferredPage' => $maxProductReferredPage,
            'average' => $average,
            'monthReferrals' => $monthReferrals,
        ];

        $content = $this->twig->render(
            '@OdiseoSyliusReferralsPlugin/Shop/Account/ReferralsProgram/Index/Statistics/_template.html.twig',
            $data
        );

        return new Response($content);
    }

    private function averageByCustomer(CustomerInterface $customer): int
    {
        $sum = $this->referralsProgramViewRepository->findViewsByCustomer($customer);
        $count = $this->referralsProgramRepository->findCountPaymentsByCustomer($customer);

        if ($count == 0 || $sum == 0) {
            return 0;
        }

        return (int) (($count / $sum) * 100);
    }
}
