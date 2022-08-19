<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AffiliateRepositoryInterface extends RepositoryInterface
{
    public function findOneByCustomerNotExpired(CustomerInterface $customer): ?AffiliateInterface;

    public function findOneByCustomerAndProductNotExpired(
        CustomerInterface $customer,
        ProductInterface $product
    ): ?AffiliateInterface;
}
