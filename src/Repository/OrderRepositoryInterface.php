<?php

declare(strict_types=1);

namespace Odiseo\SyliusReferralsPlugin\Repository;

use Odiseo\SyliusReferralsPlugin\Entity\AffiliateInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface as BaseOrderRepositoryInterface;

interface OrderRepositoryInterface extends BaseOrderRepositoryInterface
{
    public function countSalesByAffiliate(AffiliateInterface $affiliate): int;
}
