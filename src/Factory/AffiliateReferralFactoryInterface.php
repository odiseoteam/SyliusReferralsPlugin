<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Factory;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface AffiliateReferralFactoryInterface extends FactoryInterface
{
    public function createForCustomer(CustomerInterface $customer): AffiliateReferralInterface;
}
