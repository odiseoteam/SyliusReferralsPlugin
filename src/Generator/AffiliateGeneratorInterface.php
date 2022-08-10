<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Generator;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;

interface AffiliateGeneratorInterface
{
    public function generate(CustomerInterface $customer, ?ProductInterface $product): AffiliateInterface;
}
