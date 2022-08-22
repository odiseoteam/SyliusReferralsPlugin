<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Factory;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class AffiliateReferralFactory implements AffiliateReferralFactoryInterface
{
    private FactoryInterface $decoratedFactory;

    public function __construct(FactoryInterface $decoratedFactory)
    {
        $this->decoratedFactory = $decoratedFactory;
    }

    public function createNew(): object
    {
        return $this->decoratedFactory->createNew();
    }

    public function createForAffiliate(AffiliateInterface $affiliate): AffiliateReferralInterface
    {
        /** @var AffiliateReferralInterface $affiliateReferral */
        $affiliateReferral = $this->decoratedFactory->createNew();
        $affiliateReferral->setAffiliate($affiliate);
        $affiliateReferral->setTokenValue($this->generateTokenValue());

        return $affiliateReferral;
    }

    private function generateTokenValue(): string
    {
        return uniqid('ap_', true);
    }
}
