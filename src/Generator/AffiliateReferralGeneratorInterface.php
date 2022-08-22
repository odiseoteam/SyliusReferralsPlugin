<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Generator;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface AffiliateReferralGeneratorInterface
{
    public function generate(CustomerInterface $customer, ?ProductInterface $product = null): AffiliateReferralInterface;
}
