<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Generator;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface AffiliateReferralGeneratorInterface
{
    public function generate(
        AffiliateInterface $affiliate,
        ?ProductInterface $product = null
    ): AffiliateReferralInterface;
}
