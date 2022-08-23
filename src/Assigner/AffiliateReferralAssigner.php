<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Assigner;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralAwareInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Odiseo\SyliusReferralsPlugin\Repository\AffiliateReferralRepositoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class AffiliateReferralAssigner implements AffiliateReferralAssignerInterface
{
    private AffiliateReferralRepositoryInterface $affiliateReferralRepository;

    public function __construct(
        AffiliateReferralRepositoryInterface $affiliateReferralRepository
    ) {
        $this->affiliateReferralRepository = $affiliateReferralRepository;
    }

    public function assign(OrderInterface $order): void
    {
        $session = new Session();

        if (!$session->has(AffiliateReferralInterface::TOKEN_PARAM_NAME)) {
            return;
        }

        /** @var AffiliateReferralInterface|null $affiliateReferral */
        $affiliateReferral = $this->affiliateReferralRepository->findOneBy([
            'tokenValue' => $session->get(AffiliateReferralInterface::TOKEN_PARAM_NAME)
        ]);

        if (null === $affiliateReferral) {
            return;
        }

        if ($order instanceof AffiliateReferralAwareInterface) {
            $order->setAffiliateReferral($affiliateReferral);
        }

        $session->remove(AffiliateReferralInterface::TOKEN_PARAM_NAME);
    }
}
