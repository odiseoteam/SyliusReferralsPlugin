<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Factory;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface AffiliateReferralFactoryInterface extends FactoryInterface
{
    public function createForAffiliate(AffiliateInterface $affiliate): AffiliateReferralInterface;
}
