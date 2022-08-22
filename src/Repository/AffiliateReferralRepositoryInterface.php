<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateReferralInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AffiliateReferralRepositoryInterface extends RepositoryInterface
{
    public function findOneByCustomerNotExpired(CustomerInterface $customer): ?AffiliateReferralInterface;

    public function findOneByCustomerAndProductNotExpired(
        CustomerInterface $customer,
        ProductInterface $product
    ): ?AffiliateReferralInterface;
}
