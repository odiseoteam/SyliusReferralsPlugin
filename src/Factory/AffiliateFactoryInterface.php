<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Factory;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface AffiliateFactoryInterface extends FactoryInterface
{
    public function createForCustomer(CustomerInterface $customer): AffiliateInterface;
}
