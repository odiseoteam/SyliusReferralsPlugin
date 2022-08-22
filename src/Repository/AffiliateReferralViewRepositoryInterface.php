<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AffiliateReferralViewRepositoryInterface extends RepositoryInterface
{
    public function countViewsByCustomer(CustomerInterface $customer): int;
}
